@extends('layouts.customer.app')

@section('header')
<header class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Halaman Pembayaran</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('customer.carts.index') }}">Keranjang</a></li>
                <li class="breadcrumb-item active">Pembayaran</li>
            </ol>
        </div>
    </div>
</header>
@endsection

@section('content')
<section class="card">
    <div class="card-body">
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <!-- Ringkasan Pesanan -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Ringkasan Pesanan</h3>
                    </div>
                    <div class="card-body">
    <div class="table-responsive">
        <table class="table table-bordered table-sm align-middle">
            <thead class="table-light">
                <tr>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartItems as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp {{ number_format($item->price_snapshot, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->price_snapshot * $item->quantity, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end fw-bold">Total Harga</td>
                    <td class="fw-bold">Rp {{ number_format($totalPrice, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Informasi Transfer -->
<div class="card mt-4 shadow-sm border-info">
    <div class="card-header bg-info text-white d-flex align-items-center">
        <strong>Informasi Transfer</strong>
    </div>
    <div class="card-body">
        <p class="mb-3">Silakan lakukan transfer ke salah satu rekening berikut:</p>

        <table class="table table-borderless table-sm mb-3">
            <tbody>
                <tr>
                    <td class="fw-semibold text-dark" style="width: 120px;">Bank BCA</td>
                    <td class="text-primary fw-bold">123-456-7890</td>
                    <td class="fst-italic">a.n. PT Contoh Toko</td>
                </tr>
                <tr>
                    <td class="fw-semibold text-dark">Bank Mandiri</td>
                    <td class="text-primary fw-bold">987-654-3210</td>
                    <td class="fst-italic">a.n. PT Contoh Toko</td>
                </tr>
                <tr>
                    <td class="fw-semibold text-dark">OVO / GoPay</td>
                    <td class="text-primary fw-bold">0812-3456-7890</td>
                    <td class="fst-italic">a.n. PT Contoh Toko</td>
                </tr>
            </tbody>
        </table>

        <div class="d-flex align-items-center text-danger small fst-italic">
            Pastikan nominal transfer sesuai <strong class="ms-1">Total Harga</strong> di atas.
        </div>
    </div>
</div>

                    </div>
                </div>
            </div>

            <!-- Form Pembayaran -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Pembayaran & Pengiriman</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('customer.checkout.process') }}" method="POST" enctype="multipart/form-data" id="paymentForm">
                            @csrf

                            @foreach($cartItems as $item)
                                <input type="hidden" name="cart_ids[]" value="{{ $item->id }}">
                            @endforeach

                            <div class="form-group mb-3">
                                <label for="shipping_address" class="form-label">Alamat Pengiriman <span class="text-danger">*</span></label>
                                <textarea name="shipping_address" id="shipping_address" class="form-control" rows="3" required>{{ old('shipping_address', auth()->guard('customer')->user()->address ?? '') }}</textarea>
                                @error('shipping_address')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="payer_name" class="form-label">Atas Nama (Sesuai Rekening/E-wallet) <span class="text-danger">*</span></label>
                                <input type="text" name="payer_name" id="payer_name" class="form-control" value="{{ old('payer_name', auth()->guard('customer')->user()->name) }}" required>
                                @error('payer_name')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="payment_date" class="form-label">Tanggal Pembayaran <span class="text-danger">*</span></label>
                                <input type="date" name="payment_date" id="payment_date" class="form-control" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                                @error('payment_date')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="payment_method" class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                                <select name="payment_method" id="payment_method" class="form-control" required>
                                    <option value="">Pilih Metode Pembayaran</option>
                                    <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                    <option value="ewallet" {{ old('payment_method') == 'ewallet' ? 'selected' : '' }}>E-wallet</option>
                                </select>
                                @error('payment_method')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3" id="proofUploadSection">
                                <label for="proof_of_payment" class="form-label">Bukti Transfer/Pembayaran <span class="text-danger">*</span></label>
                                <input type="file" name="proof_of_payment" id="proof_of_payment" class="form-control" accept="image/*" required>
                                <small class="form-text text-muted">Format: JPEG, PNG, JPG, GIF (Maks. 2MB)</small>
                                @error('proof_of_payment')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tombol -->
<div class="row mt-2 g-2">
    <div class="col-12 col-md-6 d-grid">
        <button type="submit" class="btn btn-success w-100">
            <i class="fas fa-check-circle me-2"></i>Konfirmasi Pembayaran
        </button>
    </div>
    <div class="col-12 col-md-6 d-grid">
        <a href="{{ route('customer.carts.index') }}" class="btn btn-outline-secondary w-100">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Keranjang
        </a>
    </div>
</div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethod = document.getElementById('payment_method');
    const proofUploadSection = document.getElementById('proofUploadSection');
    const proofInput = document.getElementById('proof_of_payment');

    // Karena COD dihapus, bukti pembayaran selalu wajib.
    // Menghilangkan logika `toggleProofUpload` dan input tersembunyi.
    proofUploadSection.style.display = 'block';
    proofInput.setAttribute('required', 'required');

    // Validasi tanggal
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        const today = new Date().toISOString().split('T')[0];
        const paymentDate = document.getElementById('payment_date').value;
        if (paymentDate > today) {
            e.preventDefault();
            alert('Tanggal pembayaran tidak boleh lebih dari hari ini.');
            return false;
        }
    });
});
</script>

<style>
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}
.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}
.form-label {
    font-weight: 500;
}
.alert-info {
    background-color: #e7f3fe;
    border-color: #bee5eb;
}
</style>
@endsection
