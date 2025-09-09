<?php

namespace App\Http\Controllers\Auth\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function index()
    {
        return view('auth.customer.register');
    }

    public function store(Request $request)
    {
        // 🔹 Tambah pesan khusus jika email sudah terdaftar
        if (Customer::where('email', $request->email)->exists()) {
            return back()->withInput()->with('error', 'Email sudah terdaftar, silakan gunakan email lain.');
        }

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:customers',
            'password' => 'required|string|min:8|confirmed',
            'phone'    => 'required|string|max:20',
            'address'  => 'required|string',
        ]);

        Customer::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => $validated['password'],
            'phone'    => $validated['phone'],
            'address'  => $validated['address'],
        ]);

        return redirect()->route('customer.auth.login.index')
                         ->with('registered', true);
    }
}
