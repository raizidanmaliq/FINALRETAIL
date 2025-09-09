@extends('layouts.common.app')

@section('content')
<div class="container mt-5 mb-5">
    {{-- Mengubah tampilan tombol kembali agar lebih besar dan sesuai dengan contoh --}}
    <div class="mb-4">
    <a href="{{ route('customer.orders.index') }}"
       class="d-inline-flex align-items-center text-decoration-none fw-bold"
       style="color:#A34A4A; font-size:1.25rem;">
        <i class="fas fa-arrow-left me-2" style="font-size:2.5rem;"></i> Back
    </a>
</div>


    <div class="card shadow mb-4 mt-3" style="border-color: #A34A4A;">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm" style="border-color: #A34A4A;">
                        <div class="card-header text-white" style="background-color: #A34A4A;">
                            <h3 class="card-title h5 mb-0"><i class="fas fa-truck me-2"></i> Informasi Pengiriman</h3>
                        </div>
                        <div class="card-body">
                            <p><strong>Kode Pesanan:</strong> {{ $order->order_code }}</p>
                            <p><strong>Status:</strong>
                                <span class="badge px-2 py-1 text-uppercase
                                    @if($order->order_status == 'pending') bg-warning text-dark
                                    @elseif($order->order_status == 'processing') bg-info text-white
                                    @elseif($order->order_status == 'shipped') bg-primary text-white
                                    @elseif($order->order_status == 'completed') bg-success text-white
                                    @else bg-danger text-white
                                    @endif">
                                    {{ ucfirst($order->order_status) }}
                                </span>
                            </p>
                            <hr style="border-top: 1px solid #A34A4A;">
                            <p><strong>Nama Penerima:</strong> {{ $order->receiver_name }}</p>
                            <p><strong>Telepon Penerima:</strong> {{ $order->receiver_phone }}</p>
                            <p><strong>Email Penerima:</strong> {{ $order->receiver_email }}</p>
                            <p><strong>Alamat Pengiriman:</strong><br> {{ $order->receiver_address }}</p>
                            <p><strong>Tanggal Pesan:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-success" style="border-color: #A34A4A;">
                        <div class="card-header text-white" style="background-color: #A34A4A;">
                            <h3 class="card-title h5 mb-0"><i class="fas fa-credit-card me-2"></i> Informasi Pembayaran</h3>
                        </div>
                        <div class="card-body">
                            @if($order->payment)
                                <p><strong>Metode:</strong> {{ Str::title(str_replace('_', ' ', $order->payment->payment_method)) }}</p>
                                <p><strong>Jumlah:</strong> <span class="text-success fw-bold">Rp {{ number_format($order->payment->amount, 0, ',', '.') }}</span></p>

                                <p><strong>Tanggal Bayar:</strong> {{ \Carbon\Carbon::parse($order->payment->payment_date)->format('d M Y') }}</p>
                                @if($order->payment->proof)
                                    <p class="mt-3"><strong>Bukti:</strong>
                                        <a href="#" class="btn btn-sm text-white" style="background-color: #A34A4A;" data-toggle="modal" data-target="#proofModal">
                                            <i class="fas fa-eye me-1"></i> Lihat Bukti
                                        </a>
                                    </p>
                                @else
                                    <p class="text-danger"><i class="fas fa-times-circle me-1"></i> Belum ada bukti pembayaran.</p>
                                @endif
                            @else
                                <p class="text-danger"><i class="fas fa-exclamation-circle me-1"></i> Belum ada informasi pembayaran.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3" style="border-color: #A34A4A;">
                <div class="card-header text-white" style="background-color: #A34A4A;">
                    <h3 class="card-title h5 mb-0"><i class="fas fa-box me-2"></i> Produk Dipesan</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm align-middle mb-0">
                            <thead class="text-white" style="background-color: #A34A4A;">
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
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @php
                                                $imagePath = ($item->product && $item->product->images && count($item->product->images) > 0)
                                                                ? asset($item->product->images[0]->image_path)
                                                                : asset('images/no-image.png');
                                            @endphp
                                            <img src="{{ $imagePath }}"
                                                alt="{{ $item->product->name ?? '' }}"
                                                class="img-fluid rounded me-3"
                                                style="width:80px; height:80px; object-fit:cover;"
                                                onerror="this.src='{{ asset('images/no-image.png') }}'">
                                            <h6 class="mb-0">{{ $item->product->name }}</h6>
                                        </div>
                                    </td>
                                    <td>{{ $item->color ?? '-' }}</td>
                                    <td>{{ $item->size ?? '-' }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="background-color: #F8F9FC;">
                                    <td colspan="5" class="text-end fw-bold">Total Keseluruhan:</td>
                                    <td class="text-end fw-bold text-success">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="proofModal" tabindex="-1" aria-labelledby="proofModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow">
            <div class="modal-header text-white" style="background-color: #A34A4A;">
                <h5 class="modal-title" id="proofModalLabel"><i class="fas fa-image me-1"></i> Bukti Pembayaran</h5>
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
@endsection
