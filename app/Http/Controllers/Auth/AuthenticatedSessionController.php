<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Illuminate\Support\Str;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('users.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();
        $user = auth()->user();
        $sessionCart = Session::get('cart', []);
        $request->session()->regenerate();

        if ($user) {
            $dbCart = $user->cart()->with('products')->first();
            if ($dbCart && $dbCart->products->isNotEmpty() && !empty($sessionCart)) {
                Session::put('temp_cart', $sessionCart);
                return redirect()->route("cart.conflict");
            } else {
                if (!empty($sessionCart)) {
                    foreach ($sessionCart as $productId => $details) {
                        $dbCart->products()->attach($productId, ['id' => Str::uuid(), 'quantity' => $details['quantity']]);
                    }
                    Session::forget('cart');
                }
            }
        }


        return redirect('/');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->to('/');
    }
}
