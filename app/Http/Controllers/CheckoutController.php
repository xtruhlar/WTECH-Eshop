<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $productsData = [];
        $total = 0;

        if (!$request->user()) {
            // Handle guest users with session cart
            $cart = Session::get('cart', []);
            foreach ($cart as $productId => $details) {
                $productsData[$productId] = [
                    'quantity' => $details['quantity']
                ];
            }
        } else {
            // Handle logged-in users with cart stored in database
            $user = $request->user();
            $cart = $user->cart()->with('products')->first();

            if ($cart) {
                foreach ($cart->products as $product) {
                    $productsData[$product->id] = [
                        'quantity' => $product->pivot->quantity
                    ];
                }
            }
        }

        // Check if there are products to process
        if (empty($productsData)) {
            return back()->with('error', 'Your cart is empty.');
        }

        // Load products in one query using the array of IDs
        $productIds = array_keys($productsData);
        $products = Product::findMany($productIds);

        // Calculate total and map quantity to each product object
        foreach ($products as $product) {
            $product->quantity = $productsData[$product->id]['quantity'];
            $total += $product->price * $product->quantity;
        }

        return view('checkout', [
            'products' => $products,
            'total' => $total
        ]);
    }
}
