<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Inventory\Product;
use App\Models\Inventory\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Tampilkan halaman keranjang belanja untuk pengguna yang sudah login atau tamu.
     */
    public function index()
    {
        if (auth()->guard('customer')->check()) {
            $cartItems = auth()->guard('customer')->user()
                ->carts()
                ->with(['product', 'variant'])
                ->get();
        } else {
            $cartSession = session()->get('cart', []);
            $cartItems = collect($cartSession)->map(function ($item, $key) {
                // Konversi array sesi menjadi objek untuk konsistensi di Blade
                $obj = (object) $item;
                $obj->id = $key; // Tambahkan ID dari key sesi
                $obj->product = Product::find($obj->product_id);
                $obj->variant = ProductVariant::find($obj->variant_id);
                return $obj;
            });
        }

        return view('customer.carts.index', compact('cartItems'));
    }

    /**
     * Tambahkan produk ke keranjang. Bekerja untuk tamu dan pengguna yang sudah login.
     */
    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'color'    => 'required|string',
            'size'     => 'required|string',
        ]);

        $variant = ProductVariant::where('product_id', $product->id)
                                 ->where('color', $request->color)
                                 ->where('size', $request->size)
                                 ->first();

        if (!$variant) {
            return redirect()->back()->with('error', 'Varian produk tidak valid.');
        }

        if (auth()->guard('customer')->check()) {
            $customer = auth()->guard('customer')->user();

            // Perbaikan: Cari item keranjang berdasarkan product_id dan variant_id
            $cart = $customer->carts()
                             ->where('product_id', $product->id)
                             ->where('variant_id', $variant->id)
                             ->first();

            if ($cart) {
                // Jika produk dengan varian yang sama sudah ada, perbarui kuantitas saja
                $cart->increment('quantity', $request->quantity);
            } else {
                // Jika belum ada, buat entri baru
                Cart::create([
                    'customer_id'    => $customer->id,
                    'product_id'     => $product->id,
                    'variant_id'     => $variant->id,
                    'quantity'       => $request->quantity,
                    'price_snapshot' => $product->selling_price,
                ]);
            }
        } else {
            // Pengguna tamu: simpan di session
            $cart = session()->get('cart', []);
            $itemKey = $product->id . '_' . $variant->id;

            if (isset($cart[$itemKey])) {
                $cart[$itemKey]['quantity'] += $request->quantity;
            } else {
                $cart[$itemKey] = [
                    'product_id'    => $product->id,
                    'product_name'  => $product->name,
                    'image'         => $product->images->first()->image_path ?? asset('images/no-image.png'),
                    'selling_price' => $product->selling_price,
                    'quantity'      => $request->quantity,
                    'variant_id'    => $variant->id,
                    'color'         => $variant->color,
                    'size'          => $variant->size,
                ];
            }
            session()->put('cart', $cart);
        }

        return redirect()->route('customer.carts.index')
                         ->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    /**
     * Hapus item dari keranjang. Bekerja untuk tamu dan pengguna yang sudah login.
     */
    public function remove($cartId)
    {
        if (auth()->guard('customer')->check()) {
            $cart = auth()->guard('customer')->user()->carts()->findOrFail($cartId);
            $cart->delete();
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$cartId])) {
                unset($cart[$cartId]);
                session()->put('cart', $cart);
            }
        }
        return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    /**
     * Perbarui jumlah untuk item yang dipilih. Bekerja untuk tamu dan pengguna yang sudah login.
     */
    public function updateSelected(Request $request)
    {
        $quantities = $request->input('quantities');

        if (auth()->guard('customer')->check()) {
            $customer = auth()->guard('customer')->user();
            foreach ($quantities as $cartId => $quantity) {
                $cart = $customer->carts()->find($cartId);
                if ($cart) {
                    $cart->update(['quantity' => $quantity]);
                }
            }
        } else {
            $cartSession = session()->get('cart', []);
            foreach ($quantities as $id => $quantity) {
                if (isset($cartSession[$id])) {
                    $cartSession[$id]['quantity'] = (int)$quantity;
                }
            }
            session()->put('cart', $cartSession);
        }

        return response()->json([
            'success' => true,
            'message' => 'Jumlah produk berhasil diperbarui.'
        ]);
    }

    /**
     * Pindahkan item keranjang dari session ke database setelah login.
     */
    public function migrateCart()
{
    $customer = auth()->guard('customer')->user();
    $cartSession = session()->get('cart', []);

    foreach ($cartSession as $item) {
        // PERBAIKAN: Cari item di database berdasarkan product_id DAN variant_id
        $cart = $customer->carts()
            ->where('product_id', $item['product_id'])
            ->where('variant_id', $item['variant_id']) // Tambahkan filter variant_id
            ->first();

        if ($cart) {
            // Jika item dengan varian yang sama sudah ada di database, tambahkan kuantitasnya
            $cart->increment('quantity', $item['quantity']);
        } else {
            // Jika belum ada, buat entri baru di database
            Cart::create([
                'customer_id'    => $customer->id,
                'product_id'     => $item['product_id'],
                'quantity'       => $item['quantity'],
                'price_snapshot' => $item['selling_price'],
                'variant_id'     => $item['variant_id'],
            ]);
        }
    }

    session()->forget('cart');
}
}
