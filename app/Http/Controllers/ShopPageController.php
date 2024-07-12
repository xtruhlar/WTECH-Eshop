<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Product;
use Illuminate\Http\Request;

include app_path('Helpers/availabilityEnumDecoder.php');


class ShopPageController extends Controller
{
    public function index(Request $request)
    {
        $productsQuery = Product::with(['category', 'manufacturer']);
        $manufacturers = Manufacturer::get();
        $categories = Category::get();


        $perPage = config("app.items_per_page");

        $maxPrice = Product::max("price");
        $maxPrice = ceil($maxPrice);

        $minPrice = Product::min("price");
        $minPrice = ceil($minPrice);

        // get current filter values
        $price = $request->input('cena', $maxPrice);
        $availability = $request->input('dostupnost', "all");
        $manufacturerSlug = $request->input('vyrobca', "all");
        $categorySlug = $request->input('kategoria', "all");
        $orderBy = $request->input('zoradit-podla', "default");

        // Apply filters 
        if ($price) {
            $productsQuery->where('price', '<=', $price);
        }

        if ($availability && $availability != "all") {
            $enumavailability = avaliabilityFilterValuesToEnum($availability);
            $productsQuery->where('availability', $enumavailability);
        }

        if ($manufacturerSlug && $manufacturerSlug != "all") {
            $productsQuery->whereHas('manufacturer', function ($query) use ($manufacturerSlug) {
                $query->where('slug', $manufacturerSlug);
            });
        }

        if ($categorySlug && $categorySlug != "all") {
            $productsQuery->whereHas('category', function ($query) use ($categorySlug) {
                $query->where('slug', $categorySlug);
            });
        }


        if ($orderBy) {
            if ($orderBy == "nazov-vzostupne") {
                $productsQuery->orderBy("title", "asc");
            }

            if ($orderBy == "nazov-zostupne") {
                $productsQuery->orderBy("title", "desc");
            }

            if ($orderBy == "cena-najlacnejsie") {
                $productsQuery->orderBy("price", "asc");
            }

            if ($orderBy == "cena-najdrahsie") {
                $productsQuery->orderBy("price", "desc");
            }

            if ($orderBy == "default") {
                $request->query->remove('zoradit-podla');
            }
        }

        // Paginate the results
        $products = $productsQuery->paginate($perPage);

        // Current page is automatically determined by Laravel
        $currentPage = $products->currentPage();

        // Total number of pages
        $totalPages = $products->lastPage();

        return view('shop', [
            'products' => $products,
            'manufacturers' => $manufacturers,
            'categories' => $categories,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
            'orderBy' => $orderBy,
            'selectedFilters' => (object)[
                'maxPrice' => $maxPrice,
                'minPrice' => $minPrice,
                'price' => $price,
                'availability' => $availability,
                'manufacturer' => $manufacturerSlug,
                'category' => $categorySlug,
            ]
        ]);
    }
}
