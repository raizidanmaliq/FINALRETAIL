<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Cart;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CheckoutController extends Controller
{
    /**
     * Tampilkan halaman pembayaran dengan item yang dipilih.
     */
    public function prepare(Request $request)
    {
        // Validasi input
        $request->validate([
            'cart_ids' => 'required|array|min:1',
            'cart_ids.*' => 'exists:carts,id'
        ]);

        $customer = auth()->guard('customer')->user();

        // Ambil item cart yang dipilih
        $cartItems = Cart::whereIn('id', $request->cart_ids)
                            ->where('customer_id', $customer->id)
                            ->with('product')
                            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.carts.index')
                                 ->with('error', 'Tidak ada produk yang dipilih untuk checkout.');
        }

        // Hitung total harga
        $totalPrice = $cartItems->sum(function ($item) {
            return $item->price_snapshot * $item->quantity;
        });

        // Tampilkan halaman pembayaran
        return view('customer.checkout.payment', compact('cartItems', 'totalPrice'));
    }

    /**
     * Proses pembayaran dan buat pesanan.
     */
    public function process(Request $request)
    {
        // Validasi input
        $request->validate([
            'cart_ids' => 'required|array|min:1',
            'cart_ids.*' => 'exists:carts,id',
            'shipping_address' => 'required|string|max:500',
            'payment_method' => 'required|string|in:bank_transfer,ewallet',
            'payer_name' => 'required|string|max:100',
            'payment_date' => 'required|date|before_or_equal:today',
            'proof_of_payment' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $customer = auth()->guard('customer')->user();

        // Ambil item cart yang dipilih
        $cartItems = Cart::whereIn('id', $request->cart_ids)
                            ->where('customer_id', $customer->id)
                            ->with('product')
                            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.carts.index')
                                 ->with('error', 'Produk tidak valid atau sudah dihapus.');
        }

        DB::beginTransaction();

        try {
            // Hitung total harga
            $totalPrice = $cartItems->sum(function($item) {
                return $item->price_snapshot * $item->quantity;
            });

            // Simpan bukti pembayaran ke path yang diinginkan
            $storagePath = 'back_assets/img/cms/payments/';
            $proofPath = $request->file('proof_of_payment')->move(
                public_path($storagePath),
                $request->file('proof_of_payment')->hashName()
            );
            $databasePath = $storagePath . basename($proofPath);

            // 1. Buat order baru
            $order = Order::create([
                'customer_id' => $customer->id,
                'order_code' => 'ORD-' . time() . '-' . Str::upper(Str::random(5)),
                'total_price' => $totalPrice,
                'shipping_address' => $request->shipping_address,
                'order_status' => 'pending', // Status awal selalu 'pending'
            ]);

            // 2. Pindahkan item dari cart ke order_items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price_snapshot,
                    'subtotal' => $item->price_snapshot * $item->quantity
                ]);
            }

            // 3. Buat record payment
            $paymentData = [
                'order_id' => $order->id,
                'amount' => $totalPrice,
                'payment_method' => $request->payment_method,
                'payer_name' => $request->payer_name,
                'payment_date' => $request->payment_date,
                'proof' => $databasePath,
            ];

            Payment::create($paymentData);

            // 4. Hapus item cart yang sudah dicheckout
            Cart::whereIn('id', $request->cart_ids)->delete();

            DB::commit();

            // ✅ Bagian yang diubah untuk mengirim ke WhatsApp
            // Ganti dengan nomor WhatsApp Anda (format internasional tanpa '+')
            $whatsappNumber = '6285323227747';

            // Bangun pesan untuk WhatsApp dengan format URL
            $message = "Halo, saya *{$customer->name}* (%2A{$customer->phone}%2A).%0A%0ASaya telah melakukan pemesanan di website dengan detail:%0A%0A*Kode Pesanan:* {$order->order_code}%0A*Total Pembayaran:* Rp " . number_format($totalPrice, 0, ',', '.') . "%0A%0A*Detail Produk:*%0A";

            foreach ($cartItems as $item) {
                $message .= "- {$item->product->name} ({$item->quantity} pcs) - Rp " . number_format($item->price_snapshot * $item->quantity, 0, ',', '.') . "%0A";
            }

            $message .= "%0A*Alamat Pengiriman:*%0A{$request->shipping_address}%0A%0ASaya sudah mengunggah bukti pembayaran dan sedang menunggu konfirmasi. Terima kasih.";

            // Buat tautan WhatsApp
            $whatsappLink = "https://wa.me/{$whatsappNumber}?text={$message}";

            // Redirect pengguna ke tautan WhatsApp
            return redirect()->away($whatsappLink);

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('customer.carts.index')
                                 ->with('error', 'Checkout gagal: ' . $e->getMessage())
                                 ->withInput();
        }
    }
}
