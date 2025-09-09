<?php

namespace App\Http\Controllers\Auth\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Customer\CartController;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.customer.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::guard('customer')->attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            // Migrasi keranjang tamu ke database
            (new CartController())->migrateCart();

            // Hapus atau komen kode ini agar tidak mengalihkan ke checkout
            // $customer = Auth::guard('customer')->user();
            // $cartItems = $customer->carts()->get();
            // $cartIds = $cartItems->pluck('id')->toArray();

            // if (!empty($cartIds)) {
            //     return redirect()->route('customer.checkout.prepare', ['cart_ids' => $cartIds])
            //                     ->with('success', 'Berhasil login, silakan lanjut checkout.');
            // }

            // Ganti pengalihan default
            return redirect()->intended('customer/carts')
                             ->with('success', 'Berhasil login, selamat datang kembali!');
        }

        return back()->with('error', 'Gagal login, email atau password salah.');
    }

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')
            ->with('success', 'Berhasil logout, sampai jumpa lagi!');
    }
}
