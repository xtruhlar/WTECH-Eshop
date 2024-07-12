<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Cart;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('components.header', function ($view) {
            $totalQuantity = 0;

            if (!Auth::check()) {
                $cart = Session::get("cart", []);
                $totalQuantity = sizeof($cart);
            } else {
                $cart = Auth::user()->cart;
                if ($cart == null) {
                    $totalQuantity = 0;
                } else {
                    $totalQuantity = $cart->products->count();
                }
            }
            // count only products id, not quantity

            $view->with('totalQuantity', $totalQuantity);
        });
    }
}
