@extends('layouts.admin.app')

@section('header')
<header class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Detail Pesanan</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.cms.customer-orders.index') }}">Pesanan Pelanggan</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </div>
    </div>
</header>
@endsection

@section('content')
<section class="card shadow mb-4 mt-3" style="border-color: #A34A4A;">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm" style="border-color: #A34A4A;">
                    <div class="card-header text-white" style="background-color: #A34A4A;">
                        <h3 class="card-title"><i class="fas fa-truck"></i> Informasi Pengiriman</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Kode Pesanan:</strong> {{ $order->order_code }}</p>
                        <p><strong>Status:</strong>
                            <span class="badge px-2 py-1 text-uppercase
                                @if($order->order_status == 'pending') bg-warning
                                @elseif($order->order_status == 'processing') bg-info
                                @elseif($order->order_status == 'shipped') bg-primary
                                @elseif($order->order_status == 'completed') bg-success
                                @else bg-danger
                                @endif">
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </p>
                        <p><strong>Nama Pelanggan:</strong> {{ $order->customer->name ?? 'N/A' }}</p>
                        <hr>
                        <p><strong>Nama Penerima:</strong> {{ $order->receiver_name }}</p>
                        <p><strong>Telepon Penerima:</strong> {{ $order->receiver_phone }}</p>
                        <p><strong>Email Penerima:</strong> {{ $order->receiver_email }}</p>
                        <p><strong>Alamat Pengiriman:</strong><br> {{ $order->receiver_address }}</p>
                        <p><strong>Tanggal Pesan:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card shadow-sm" style="border-color: #A34A4A;">
                    <div class="card-header text-white" style="background-color: #A34A4A;">
                        <h3 class="card-title"><i class="fas fa-credit-card"></i> Informasi Pembayaran</h3>
                    </div>
                    <div class="card-body">
                        @if($order->payment)
                            <p><strong>Metode:</strong> {{ Str::title(str_replace('_', ' ', $order->payment->payment_method)) }}</p>
                            <p><strong>Jumlah:</strong> <span class="text-success fw-bold">Rp {{ number_format($order->payment->amount, 0, ',', '.') }}</span></p>

                            <p><strong>Tanggal Bayar:</strong> {{ \Carbon\Carbon::parse($order->payment->payment_date)->format('d M Y') }}</p>

                            @if($order->payment->proof)
                                <p><strong>Bukti:</strong>
                                    <a href="#" class="btn btn-sm text-white" style="background-color: #A34A4A;" data-toggle="modal" data-target="#proofModal">
                                        <i class="fas fa-eye"></i> Lihat Bukti
                                    </a>
                                </p>
                            @else
                                <p class="text-danger"><i class="fas fa-times-circle"></i> Belum ada bukti pembayaran.</p>
                            @endif
                        @else
                            <p class="text-danger"><i class="fas fa-exclamation-circle"></i> Belum ada informasi pembayaran.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3" style="border-color: #A34A4A;">
            <div class="card-header text-white" style="background-color: #A34A4A;">
                <h3 class="card-title"><i class="fas fa-box"></i> Produk Dipesan</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle mb-0">
                        <thead class="table-light"> {{-- Perubahan di sini, menggunakan class table-light --}}
                            <tr>
                                <th>Produk</th>
                                <th>Warna</th>
                                <th>Ukuran</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->color ?? '-' }}</td>
                                <td>{{ $item->size ?? '-' }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-light">
                                <td colspan="5" class="text-end fw-bold">Total Keseluruhan:</td>
                                <td class="text-end fw-bold text-success">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="proofModal" tabindex="-1" aria-labelledby="proofModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow">
            <div class="modal-header text-white" style="background-color: #A34A4A;">
                <h5 class="modal-title" id="proofModalLabel"><i class="fas fa-image"></i> Bukti Pembayaran</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center bg-light">
                @if($order->payment && $order->payment->proof)
                    <img src="{{ asset($order->payment->proof) }}" alt="Bukti Pembayaran" class="img-fluid rounded shadow-sm">
                @else
                    <p class="text-danger">Bukti pembayaran tidak ditemukan.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075);
    border: 1px solid rgba(0,0,0,.125);
}
.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid rgba(0,0,0,.125);
}
</style>
@endsection
