@extends('layouts.customer.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Detail Pesanan</h1>
            <a href="{{ route('customer.orders.index') }}" class="text-blue-600 hover:underline">
                &larr; Kembali
            </a>
        </div>

        {{-- Info Pesanan --}}
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-3">Informasi Pesanan</h2>
            <p><strong>Kode:</strong> {{ $order->order_code }}</p>
            <p><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
            <p><strong>Status Pesanan:</strong>
                <span class="px-2 py-1 rounded-full text-sm
                    @if($order->order_status == 'pending') bg-yellow-100 text-yellow-800
                    @elseif($order->order_status == 'processing') bg-blue-100 text-blue-800
                    @elseif($order->order_status == 'shipped') bg-indigo-100 text-indigo-800
                    @elseif($order->order_status == 'completed') bg-green-100 text-green-800
                    @endif">
                    {{ ucfirst($order->order_status) }}
                </span>
            </p>
            <p><strong>Alamat Pengiriman:</strong> {{ $order->shipping_address }}</p>
        </div>

        {{-- Info Produk --}}
        <h2 class="text-xl font-semibold text-gray-700 mb-3">Produk Dipesan</h2>
        <div class="overflow-x-auto mb-6">
            <table class="w-full border border-gray-200 rounded-lg">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm">
                        <th class="py-2 px-4 text-left">Produk</th>
                        <th class="py-2 px-4 text-center">Jumlah</th>
                        <th class="py-2 px-4 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @foreach($order->items as $item)
                    <tr class="border-b">
                        <td class="py-2 px-4">{{ $item->product->name }}</td>
                        <td class="py-2 px-4 text-center">{{ $item->quantity }}</td>
                        <td class="py-2 px-4 text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Ringkasan --}}
        <div class="text-right">
            <p class="text-lg font-semibold">
                Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}
            </p>
        </div>

        {{-- Info Pembayaran (jika ada) --}}
        @if($order->payment)
            <div class="mt-8">
                <h2 class="text-xl font-semibold text-gray-700 mb-3">Informasi Pembayaran</h2>
                <p><strong>Metode:</strong> {{ Str::title(str_replace('_', ' ', $order->payment->payment_method)) }}</p>
                <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($order->payment->payment_date)->format('d M Y') }}</p>
                @if($order->payment->proof_of_payment)
                    <div class="mt-3">
                        <p class="text-gray-700 font-medium">Bukti Pembayaran:</p>
                        <img src="{{ Storage::url($order->payment->proof_of_payment) }}"
                             alt="Bukti Pembayaran" class="max-w-sm rounded shadow">
                    </div>
                @endif
            </div>
        @endif

    </div>
</div>
@endsection
