<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;


class CartController extends Controller
{
    public function add(Request $request)
    {
        $product = Product::find($request->product_id);
        $quantity = $request->quantity;

        if ($request->user() == null) {
            $cart = Session::get('cart', []);


            if (isset($cart[$product->id])) {

                $cart[$product->id]['quantity'] += $quantity;
            } else {
                $cart[$product->id] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity
                ];
            }

            Session::put('cart', $cart);
        } else {
            $user = $request->user();
            $cart = $user->cart;

            if (!$cart) {
                $cart = $user->cart()->create();
            }

            if ($user->cart == null) {
                DB::table('carts')->insert([
                    'id' => time() * rand(1, 1000),
                    'user_id' => $user->id,
                ]);
                $cart = Cart::where('user_id', $user->id)->first();
                $user->cart = $cart;
            }

            $pivot = $cart->products()->where('product_id', $product->id)->first();

            if ($pivot) {
                $cart->products()->updateExistingPivot($product->id, ['quantity' => $pivot->pivot->quantity + $quantity]);
            } else {
                $cart->products()->attach($product->id, ['id' => (string) Str::uuid(), 'quantity' => $quantity]);
            }
        }

        return back()->with('success', '"' . $product->title . '"' . ' bol pridaný do košíka!');
    }

    public function remove(Request $request)
    {
        $product = Product::find($request->product_id);

        if (!$product) {
            return back()->with('error', 'Product not found!');
        }

        if (!$request->user()) {

            $cart = Session::get('cart', []);

            if (isset($cart[$product->id])) {
                unset($cart[$product->id]);
                Session::put('cart', $cart);
            }
        } else {

            $cart = $request->user()->cart;

            if (!$cart) {
                $cart = $request->user()->cart()->create();
            }

            $cart->products()->detach($product->id);
        }

        return back()->with('success', '"' . $product->title . '"' . ' bol odstránený z košíka!');
    }

    public function refresh(Request $request)
    {

        $quantities = $request->input('quantity');
        $product_id = $request->input('product_id');
        $product = Product::find($product_id);

        if ($product !== null) {
            if (!$request->user()) {

                $cart = Session::get('cart', []);

                if (isset($cart[$product_id])) {
                    $cart[$product_id]['quantity'] = $quantities[$product_id];
                } else {
                    $cart[$product_id] = [
                        'product_id' => $product_id,
                        'quantity' => $quantities[$product_id],
                    ];
                }

                Session::put('cart', $cart);
            } else {
                $cart = $request->user()->cart()->firstOrCreate();

                $pivot = $cart->products()->where('product_id', $product->id)->first();
                if ($pivot) {
                    $cart->products()->updateExistingPivot($product->id, ['quantity' => $quantities[$product_id]]);
                } else {
                    $cart->products()->attach($product->id, ['quantity' => $quantities[$product_id]]);
                }
            }
        }

        return $this->index($request);
    }

    public function empty()
    {
        return view('empty_cart')->with('success', 'Objednávka odoslaná!');
    }


    public function index(Request $request)
    {
        $total = 0;
        $productsData = [];

        if (!$request->user()) {
            $sessionCart = Session::get("cart", []);
            foreach ($sessionCart as $productId => $details) {
                $productsData[$productId] = [
                    'quantity' => $details['quantity']
                ];
            }
        } else {
            $user = $request->user();
            $cart = $user->cart()->with('products')->firstOrCreate();
            foreach ($cart->products as $product) {
                $productsData[$product->id] = [
                    'quantity' => $product->pivot->quantity
                ];
            }
        }

        if (count($productsData) == 0) {
            return view('empty_cart');
        }

        $productIds = array_keys($productsData);
        $products = Product::findMany($productIds);

        foreach ($products as $product) {
            $quantity = $productsData[$product->id]['quantity'];
            $product->quantity = $quantity;
            $total += $product->price * $quantity;
        }

        return view('cart', [
            'products' => $products,
            'total' => $total
        ]);
    }

    public function merge()
    {
        $user = auth()->user();
        if (!$user) return redirect("/");
        $sessionCart = Session::get('temp_cart', []);
        $dbCart = $user->cart()->with('products')->first();

        $sessionProducts = [];
        $dbProducts = [];
        if (empty($sessionCart) || empty($dbCart)) {
            redirect()->route('/');
        }


        foreach ($sessionCart as $productId => $details) {
            $product = Product::find($productId);
            if ($product) {
                $sessionProducts[$productId] = [
                    'product' => $product,
                    'quantity' => $details['quantity'],
                    'source' => 'Session'
                ];
            }
        }


        if ($dbCart) {
            foreach ($dbCart->products as $product) {
                if (isset($dbProducts[$product->id])) {

                    $dbProducts[$product->id]['db_quantity'] = $product->pivot->quantity;
                } else {
                    $dbProducts[$product->id] = [
                        'product' => $product,
                        'quantity' => $product->pivot->quantity,
                        'source' => 'Database'
                    ];
                }
            }
        }

        return view('cart-merge', [
            'dbProducts' => $dbProducts,
            'sessionProducts' => $sessionProducts
        ]);
    }

    public function mergeCarts(Request $request)
    {
        $user = auth()->user();
        $sessionCart = Session::get('temp_cart', []);
        $dbCart = $user->cart()->with('products')->first();

        foreach ($sessionCart as $productId => $details) {
            $product = Product::find($productId);
            if ($product) {
                $pivot = $dbCart->products()->where('product_id', $productId)->first();
                if ($pivot) {
                    $newQuantity = $pivot->pivot->quantity + $details['quantity'];
                    $dbCart->products()->updateExistingPivot($productId, ['quantity' => $newQuantity]);
                } else {
                    $dbCart->products()->attach($productId, ['id' => Str::uuid(), 'quantity' => $details['quantity']]);
                }
            }
        }


        Session::forget('temp_cart');

        return redirect()->route('cart')->with('success', 'Košík úspešne aktualizovaný');
    }

    public function acceptCurrent(Request $request)
    {
        $user = auth()->user();
        $sessionCart = Session::get('temp_cart', []);
        $dbCart = $user->cart()->with('products')->first();


        $dbCart->products()->detach();


        foreach ($sessionCart as $productId => $details) {
            $dbCart->products()->attach($productId, ['id' => Str::uuid(), 'quantity' => $details['quantity']]);
        }


        Session::forget('cart');

        return redirect()->route('cart')->with('success', 'Košík úspešne aktualizovaný');
    }


    public function acceptAccount(Request $request)
    {
        $user = auth()->user();
        $dbCart = $user->cart()->with('products')->first();
        Session::forget('temp_cart');

        return redirect()->route('cart')->with('success', 'Košík úspešne aktualizovaný');
    }
}
