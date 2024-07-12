<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

include app_path('Helpers/availabilityEnumDecoder.php');

class SearchResultController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('hladat', '');

        $products = Product::with(['category', 'manufacturer'])
            ->where('title', 'like', '%' . $search . '%')
            ->orWhere('longDescription', 'like', '%' . $search . '%')
            ->orWhere('shortDescription', 'like', '%' . $search . '%')
            ->latest()
            ->take(config("app.items_per_page"))
            ->get();

        return view('search', [
            'products' => $products,
            'searchText' => $search,
        ]);
    }
}
