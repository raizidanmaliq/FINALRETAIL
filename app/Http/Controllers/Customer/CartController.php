<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Inventory\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = auth()->guard('customer')->user()
            ->carts()
            ->with('product')
            ->get();

        return view('customer.carts.index', compact('cartItems'));
    }

    public function add(Request $request, Product $product)
    {
        $customer = auth()->guard('customer')->user();

        $cart = $customer->carts()->where('product_id', $product->id)->first();

        if ($cart) {
            $cart->increment('quantity');
        } else {
            Cart::create([
                'customer_id'    => $customer->id,
                'product_id'     => $product->id,
                'quantity'       => 1,
                'price_snapshot' => $product->selling_price, // ✅ simpan harga saat dimasukkan
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success'    => true,
                'cart_count' => $customer->carts()->sum('quantity'),
            ]);
        }

        return redirect()->route('customer.carts.index')
            ->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function remove(Cart $cart)
    {
        $cart->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    public function updateQuantity(Request $request, Cart $cart)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart->update(['quantity' => $request->quantity]);

        return redirect()->back()->with('success', 'Jumlah produk berhasil diperbarui.');
    }

    /**
     * Proses checkout: pindahkan cart -> orders & order_items
     */
    public function updateSelected(Request $request)
    {
        $customer   = auth()->guard('customer')->user();
        $quantities = $request->quantities;

        foreach ($quantities as $cartId => $quantity) {
            $cart = Cart::where('id', $cartId)
                ->where('customer_id', $customer->id)
                ->first();

            if ($cart) {
                $cart->update(['quantity' => $quantity]);
            }
        }

        return response()->json(['success' => true]);
    }
}
