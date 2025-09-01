<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Customer;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;


class OrderController extends Controller
{
    public function index()
    {
        $orders = auth()->guard('customer')->user()
            ->orders()
            ->with('items.product', 'payment')
            ->latest()
            ->get();

        return view('customer.orders.index', compact('orders'));
    }

    // Method baru untuk menampilkan satu pesanan
    public function show(Order $order)
    {
        // Pastikan pengguna yang diautentikasi adalah pemilik pesanan ini
        if ($order->customer_id !== auth()->guard('customer')->user()->id) {
            abort(403);
        }

        $order->load('items.product', 'payment');

        return view('customer.orders.show', compact('order'));
    }

    public function uploadPayment(Request $request, Order $order)
    {
        // Pastikan pengguna yang diautentikasi adalah pemilik pesanan ini
        if ($order->customer_id !== auth()->guard('customer')->user()->id) {
            abort(403);
        }

        // Validasi input, hapus COD
        $request->validate([
            'payment_method' => 'required|in:bank_transfer,ewallet',
            'payer_name' => 'required|string|max:255',
            'payment_date' => 'required|date',
            'proof_of_payment' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('proof_of_payment')) {
            // Definisikan path penyimpanan yang sama dengan founder
            $storagePath = 'back_assets/img/cms/payments/';

            // Simpan file ke public/back_assets/img/cms/payments
            $proofPath = $request->file('proof_of_payment')->move(
                public_path($storagePath),
                $request->file('proof_of_payment')->hashName()
            );

            // Path yang akan disimpan di database
            $databasePath = $storagePath . basename($proofPath);

            // Temukan atau buat record pembayaran untuk pesanan ini
            $payment = $order->payment()->firstOrCreate(['order_id' => $order->id]);

            // Perbarui record pembayaran dengan data baru
            // Hapus status
            $payment->update([
                'amount' => $order->total_price,
                'payment_method' => $request->payment_method,
                'payer_name' => $request->payer_name,
                'payment_date' => $request->payment_date,
                'proof' => $databasePath, // ATRIBUT DIUBAH MENJADI 'proof'
            ]);

            // Perbarui status pesanan menjadi pending
            // Hapus payment_status
            $order->update([
                'order_status' => 'pending',
            ]);

            return redirect()
                ->route('customer.orders.show', $order)
                ->with('success', 'Bukti pembayaran berhasil diunggah. Pesanan akan segera diproses setelah diverifikasi.');
        }

        return back()->with('error', 'Upload bukti pembayaran gagal.');
    }

    public function invoice($id)
    {
        $customer = auth()->guard('customer')->user();
        $order = $customer->orders()
            ->with(['items.product', 'payment'])
            ->findOrFail($id);

        $pdf = PDF::loadView('customer.orders.invoice', compact('order','customer'))
                 ->setPaper('A4');

        return $pdf->stream('invoice-'.$order->id.'.pdf');
        // kalau mau langsung download: ->download('invoice-'.$order->id.'.pdf');
    }
}
