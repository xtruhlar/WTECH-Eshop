<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            $user = new User();
            $user->id = null;
        }

        $order = new Order;
        $order->id = (string) Str::uuid();
        $order->user_id = $user->id;
        $order->name = $request->name;
        $order->surname = $request->surname;
        $order->email = $request->email;
        $order->street = $request->street;
        $order->num = $request->num;
        $order->city = $request->city;
        $order->zip = $request->zip;
        $order->shipping_type_id = $request->doprava;
        $order->payment_type = $request->payment;
        $order->price = $request->total;
        $order->note = $request->note;

        $order->save();

        $cartItems = $user->id ? $user->cart->products : Session::get('cart', []);
        foreach ($cartItems as $productId => $details) {
            $quantity = $user->id ? $details->pivot->quantity : $details['quantity'];
            $product = Product::find($productId);
            $priceAtPurchase = $product->price;

            $order->products()->attach($productId, [
                'priceAtPurchace' => $priceAtPurchase * 100,
                'quantity' => $quantity
            ]);
        }


        if ($user->id) {
            $user->cart->products()->detach();
        } else {
            Session::remove("cart");
        }

        $order = Order::find($order->id);

        return view('order_success', ['order' => $order]);
    }
}
