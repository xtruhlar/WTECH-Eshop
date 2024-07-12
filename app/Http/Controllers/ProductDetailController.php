<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Ensure this is the correct path to your Product model
use Illuminate\Support\Facades\Session;

include app_path('Helpers/availabilityEnumDecoder.php');


class ProductDetailController extends Controller
{
    public function index($slug, Request $request)
    {

        $product = Product::with(["manufacturer", "category", "galleryImages"])->where('slug', $slug)->firstOrFail();
        $featuredProducts = Product::with(['category', 'manufacturer'])
            ->where('categoryId', $product->category->id)
            ->where('id', '!=', $product->id) // Exclude the current product
            ->take(4)
            ->get();


        $isInCart = false;
        $cartQuantity = 0;

        if (!$request->user()) {

            $sessionCart = Session::get('cart', []);
            if (isset($sessionCart[$product->id])) {
                $isInCart = true;
                $cartQuantity = $sessionCart[$product->id]['quantity'];
            }
        } else {

            $cart = $request->user()->cart()->with('products')->first();
            if ($cart) {
                $pivot = $cart->products()->where('product_id', $product->id)->first();
                if ($pivot) {
                    $isInCart = true;
                    $cartQuantity = $pivot->pivot->quantity;
                }
            }
        }

        return view('product_detail', [
            'product' => $product,
            'featuredProducts' => $featuredProducts,
            'isInCart' => $isInCart,
            'cartQuantity' => $cartQuantity
        ]);
    }
}
