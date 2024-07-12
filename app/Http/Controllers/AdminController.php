<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\GalleryImage;
use App\Models\Product;
use App\Models\Manufacturer;
use Illuminate\Support\Str;
use App\Enums\ProductAvailability;
use Illuminate\Support\Facades\Storage;
use Throwable;
use Illuminate\Support\Facades\Validator;

include app_path('Helpers/availabilityEnumDecoder.php');

class AdminController extends Controller
{

    protected function uploadfile($file)
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('images/products', $filename, ['disk' => 's3', 'visibility' => 'public']);

        return env("AWS_URL") . "/" . env("AWS_BUCKET") . "/" . $filePath;
    }

    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->get();
        return view('admin.view_products', [
            'products' => $products,
        ]);
    }

    public function edit($slug)
    {
        return view('admin.edit_product', [
            'product' => Product::where('slug', $slug)->firstOrFail(),
            'categories' => Category::get(),
            'manufacturers' => Manufacturer::get(),
            'availabilities' => ProductAvailability::cases(),
        ]);
    }

    public function update(Request $request, $slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $category = Category::find($request->category)->firstOrFail();
        $manufacturer = Manufacturer::find($request->manufacturer);
        if ($request->productName != null) {
            $product->title = $request->productName;
            $product->slug = Str::slug($request->productName);
        }

        $product->price = $request->price;
        $product->category()->associate($category->id);
        $product->manufacturer()->associate($manufacturer->id);
        $product->availability = $request->availability;
        $product->shortDescription = $request->shortDescription;
        $product->longDescription = $request->detailedDescription;
        if ($request->hasFile("productImage")) {
            $product->featuredImage = $this->uploadfile($request->productImage);
        }
        $galery = $request->galleryImages;

        if ($request->galleryImagesToRemove != "") {
            $imageIds = explode(',', $request->galleryImagesToRemove); // Split the string back into an array
            foreach ($imageIds as $imageId) {
                $image = GalleryImage::find($imageId);
                if ($image) {
                    GalleryImage::destroy($imageId);
                }
            }
        }
        $product->save();

        if ($request->hasFile('galleryImages')) {
            foreach ($request->file('galleryImages') as $file) {
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('images/products', $filename, ['disk' => 's3', 'visibility' => 'public']);

                $url = env("AWS_URL") . "/" . env("AWS_BUCKET") . "/" . $filePath;

                // Create and save the GalleryImage for each file
                $galleryImage = new GalleryImage([
                    'id' => Str::uuid(),
                    'productId' => $product->id,
                    'imageURL' => $this->uploadfile($file)
                ]);
                $galleryImage->save();
            }
        }

        return redirect()->back()
            ->with('success', 'Zmeny boli uložené');
    }

    public function create()
    {
        return view('admin.create_product', [
            'categories' => Category::get(),
            'manufacturers' => Manufacturer::get(),
            'availabilities' => ProductAvailability::cases(),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'productName' => 'required|string|max:255',
            'price' => 'required|numeric',
            // 'categoryID' => 'required|exists:Category,id',
            // 'manufacturer' => 'required|exists:Manufacturer,id',
            'productImage' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // 'galleryImages.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'  // Validating array of images
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Chyba pri vytváraní produktu');
        }

        $product = new Product();
        $category = Category::find($request->categoryID);
        $manufacturer = Manufacturer::find($request->manufacturer);

        $product->id = (string) Str::uuid();
        $product->slug = Str::slug($request->productName);
        $product->title = $request->productName;
        $product->price = $request->price;
        $product->category()->associate($category->id);
        $product->manufacturer()->associate($manufacturer->id);
        $product->availability = $request->availability;
        $product->shortDescription = $request->shortDescription;
        $product->longDescription = $request->detailedDescription;

        $product->featuredImage = $this->uploadfile($request->productImage);
        $galery = $request->galleryImages;

        try {
            $product->save();
        } catch (Throwable $th) {
            return redirect()->back()
                ->with('error', 'Chyba pri vytváraní produktu');
        }

        if ($request->hasFile('galleryImages')) {
            foreach ($request->file('galleryImages') as $file) {
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('images/products', $filename, ['disk' => 's3', 'visibility' => 'public']);

                $url = env("AWS_URL") . "/" . env("AWS_BUCKET") . "/" . $filePath;

                // Create and save the GalleryImage for each file
                $galleryImage = new GalleryImage([
                    'id' => Str::uuid(),
                    'productId' => $product->id,
                    'imageURL' => $this->uploadfile($file)
                ]);
                $galleryImage->save();
            }
        }

        return redirect(config('urls.admin_view_products.url'));
    }

    public function delete($productId)
    {
        Product::destroy($productId);

        return redirect(config('urls.admin_view_products.url'));
    }
}
