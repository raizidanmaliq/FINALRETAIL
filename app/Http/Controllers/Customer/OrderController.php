<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    /**
     * Tampilkan daftar semua pesanan milik customer yang login
     */
    public function index()
    {
        $customer = auth()->guard('customer')->user();

        $orders = $customer->orders()
            ->with('items.product', 'payment')
            ->latest()
            ->get();

        return view('customer.orders.index', compact('orders'));
    }

    /**
     * Tampilkan detail 1 pesanan
     */
    public function show($id)
    {
        $customer = auth()->guard('customer')->user();

        $order = $customer->orders()
            ->with('items.product', 'payment')
            ->findOrFail($id);

        return view('customer.orders.show', compact('order'));
    }

    /**
     * Upload bukti pembayaran
     */
    public function uploadPayment(Request $request, $id)
    {
        $customer = auth()->guard('customer')->user();

        // Pastikan pesanan milik customer ini
        $order = $customer->orders()->findOrFail($id);

        // Validasi input (hapus opsi COD)
        $request->validate([
            'payment_method'   => 'required|in:bank_transfer,ewallet',
            'payer_name'       => 'required|string|max:255',
            'payment_date'     => 'required|date',
            'proof_of_payment' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('proof_of_payment')) {
            // Path penyimpanan (public/back_assets/img/cms/payments)
            $storagePath = 'back_assets/img/cms/payments/';
            $proofPath = $request->file('proof_of_payment')->move(
                public_path($storagePath),
                $request->file('proof_of_payment')->hashName()
            );

            $databasePath = $storagePath . basename($proofPath);

            // Temukan atau buat record pembayaran
            $payment = $order->payment()->firstOrCreate(['order_id' => $order->id]);

            // Update data pembayaran
            $payment->update([
                'amount'         => $order->total_price,
                'payment_method' => $request->payment_method,
                'payer_name'     => $request->payer_name,
                'payment_date'   => $request->payment_date,
                'proof'          => $databasePath,
            ]);

            // Update status pesanan
            $order->update([
                'order_status' => 'pending',
            ]);

            return redirect()
                ->route('customer.orders.show', $order->id)
                ->with('success', 'Bukti pembayaran berhasil diunggah. Pesanan akan segera diproses setelah diverifikasi.');
        }

        return back()->with('error', 'Upload bukti pembayaran gagal.');
    }

    /**
     * Generate & tampilkan invoice PDF
     */
    public function invoice($id)
    {
        $customer = auth()->guard('customer')->user();

        $order = $customer->orders()
            ->with(['items.product', 'payment'])
            ->findOrFail($id);

        $pdf = PDF::loadView('customer.orders.invoice', compact('order', 'customer'))
            ->setPaper('A4');

        return $pdf->stream('invoice-' . $order->id . '.pdf');
        // Kalau mau auto download:
        // return $pdf->download('invoice-' . $order->id . '.pdf');
    }
}
