<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
include app_path('Helpers/availabilityEnumDecoder.php');

class HomepageController extends Controller
{
    public function index()
    {
        $latestProducts = Product::with(['category', 'manufacturer'])->latest()->take(4)->get();

        $randomProducts = Product::with(['category', 'manufacturer'])->inRandomOrder()->take(4)->get();
        $categories = Category::get();

        return view('homepage', [
            'latestProducts' => $latestProducts,
            'randomProducts' => $randomProducts,
            'categories' => $categories,
        ]);
    }
}
