@extends('layouts.common.app')

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
<div class="container mt-4 mb-5">
    <h5 class="fw-bold mb-4">Informasi Pembayaran</h5>

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
        <div class="col-12 col-lg-8">
            <div class="card border p-4 rounded bg-light mb-4">
                <h5 class="mb-3">
                    <strong>Ringkasan Pesanan</strong>
                </h5>
                <div class="table-responsive overflow-auto">
                    <table class="table align-middle" style="min-width: 650px;">
                        <thead class="border-bottom" style="border-color: #A34A4A !important;">
                            <tr>
                                <th scope="col" class="ps-3">Produk</th>
                                <th scope="col" class="text-center">Kuantitas</th>
                                <th scope="col" class="text-end pe-3">Harga</th>
                                <th scope="col" class="text-end pe-3">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $item)
                            <tr class="border-bottom" style="border-color: #A34A4A !important;">
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        @php
                                            $imagePath = null;
                                            if ($item->product && $item->product->images && count($item->product->images) > 0) {
                                                $imagePath = asset($item->product->images[0]->image_path);
                                            } else {
                                                $imagePath = asset('images/no-image.png');
                                            }
                                        @endphp
                                        <img src="{{ $imagePath }}"
                                            alt="{{ $item->product->name ?? '' }}"
                                            class="img-fluid rounded me-3"
                                            style="width:80px; height:80px; object-fit:cover;"
                                            onerror="this.src='{{ asset('images/no-image.png') }}'">
                                        <div>
                                            <h6 class="mb-2 fw-bold">{{ $item->product->name ?? '' }}</h6>
                                            @if($item->variant)
                                                <div class="mb-1"><small>Ukuran: {{ $item->variant->size }}</small></div>
                                                <div><small>Warna: {{ $item->variant->color }}</small></div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle text-center">{{ $item->quantity }}</td>
                                <td class="align-middle text-end pe-3">
                                    <div class="fw-medium">Rp {{ number_format($item->price_snapshot, 0, ',', '.') }}</div>
                                </td>
                                <td class="align-middle text-end pe-3 fw-bold">
                                    Rp {{ number_format($item->price_snapshot * $item->quantity, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-top" style="border-color: #A34A4A !important;">
                                <td colspan="3" class="text-end fw-bold py-3 pe-3">Total Harga</td>
                                <td class="fw-bold text-end py-3 pe-3">Rp {{ number_format($totalPrice, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="card border-info shadow-sm p-4 rounded bg-light">
                <h5 class="mb-3">
                    <strong>Informasi Transfer</strong>
                </h5>
                <p class="mb-3 text-muted">Silakan lakukan transfer ke salah satu rekening berikut:</p>
                <div class="table-responsive">
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
                </div>
                <div class="d-flex align-items-center text-danger small fst-italic">
                    Pastikan nominal transfer sesuai <strong class="ms-1">Total Harga</strong> di atas.
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4 mt-4 mt-lg-0">
            <div class="border p-4 rounded bg-light" style="position: sticky; top: 20px;">
                <h5 class="mb-3">
                    <strong>Informasi Pengiriman</strong>
                </h5>
                <form action="{{ route('customer.checkout.process') }}" method="POST" enctype="multipart/form-data" id="paymentForm" novalidate>
                    @csrf
                    @foreach($cartItems as $item)
                        <input type="hidden" name="cart_ids[]" value="{{ $item->id }}">
                    @endforeach

                    <div class="form-group mb-3">
                        <label for="receiver_name" class="form-label">Nama Penerima <span class="text-danger">*</span></label>
                        <input type="text" name="receiver_name" id="receiver_name" class="form-control" value="{{ old('receiver_name', auth()->guard('customer')->user()->name) }}">
                        <div class="invalid-feedback d-block" id="error-receiver_name"></div>
                        @error('receiver_name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="receiver_phone" class="form-label">Telepon Penerima <span class="text-danger">*</span></label>
                        <input type="tel" name="receiver_phone" id="receiver_phone" class="form-control" value="{{ old('receiver_phone', auth()->guard('customer')->user()->phone) }}">
                        <div class="invalid-feedback d-block" id="error-receiver_phone"></div>
                        @error('receiver_phone')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="receiver_email" class="form-label">Email Penerima <span class="text-danger">*</span></label>
                        <input type="email" name="receiver_email" id="receiver_email" class="form-control" value="{{ old('receiver_email', auth()->guard('customer')->user()->email) }}">
                        <div class="invalid-feedback d-block" id="error-receiver_email"></div>
                        @error('receiver_email')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="receiver_address" class="form-label">Alamat Lengkap Pengiriman <span class="text-danger">*</span></label>
                        <textarea name="receiver_address" id="receiver_address" class="form-control" rows="3">{{ old('receiver_address', auth()->guard('customer')->user()->address ?? '') }}</textarea>
                        <div class="invalid-feedback d-block" id="error-receiver_address"></div>
                        @error('receiver_address')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="payment_date" class="form-label">Tanggal Pembayaran <span class="text-danger">*</span></label>
                        <input type="date" name="payment_date" id="payment_date" class="form-control" value="{{ old('payment_date', date('Y-m-d')) }}">
                        <div class="invalid-feedback d-block" id="error-payment_date"></div>
                        @error('payment_date')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="payment_method" class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                        <select name="payment_method" id="payment_method" class="form-control">
                            <option value="">Pilih Metode Pembayaran</option>
                            <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="ewallet" {{ old('payment_method') == 'ewallet' ? 'selected' : '' }}>E-wallet</option>
                        </select>
                        <div class="invalid-feedback d-block" id="error-payment_method"></div>
                        @error('payment_method')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3" id="proofUploadSection">
                        <label for="proof_of_payment" class="form-label">Bukti Transfer/Pembayaran <span class="text-danger">*</span></label>
                        <input type="file" name="proof_of_payment" id="proof_of_payment" class="form-control" accept="image/*">
                        <small class="form-text text-muted">Format: JPEG, PNG, JPG, GIF (Maks. 2MB)</small>
                        <div class="invalid-feedback d-block" id="error-proof_of_payment"></div>
                        @error('proof_of_payment')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <input type="hidden" name="payer_name" value="{{ old('payer_name', auth()->guard('customer')->user()->name) }}">

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn w-100" style="background-color: #A34A4A; color: white; border: none;">
                            <i class="fas fa-check-circle me-2"></i>Konfirmasi Pembayaran
                        </button>
                        <a href="{{ route('customer.carts.index') }}" class="btn btn-outline-dark w-100">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Keranjang
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    .form-label {
        font-weight: 500;
    }
    .is-invalid {
        border-color: #dc3545 !important;
        padding-right: calc(1.5em + 0.75rem);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentForm = document.getElementById('paymentForm');

        const fieldNames = {
            'receiver_name': 'Nama Penerima',
            'receiver_phone': 'Telepon Penerima',
            'receiver_email': 'Email Penerima',
            'receiver_address': 'Alamat Lengkap Pengiriman',
            'payment_date': 'Tanggal Pembayaran',
            'payment_method': 'Metode Pembayaran',
            'proof_of_payment': 'Bukti Pembayaran'
        };

        const requiredFields = Object.keys(fieldNames);

        // Fungsi untuk menampilkan pesan error
        const showError = (fieldId, message) => {
            const inputElement = document.getElementById(fieldId);
            const errorElement = document.getElementById(`error-${fieldId}`);
            if (inputElement && errorElement) {
                inputElement.classList.add('is-invalid');
                errorElement.textContent = message;
                errorElement.style.color = '#dc3545';
            }
        };

        // Fungsi untuk menyembunyikan pesan error
        const hideError = (fieldId) => {
            const inputElement = document.getElementById(fieldId);
            const errorElement = document.getElementById(`error-${fieldId}`);
            if (inputElement && errorElement) {
                inputElement.classList.remove('is-invalid');
                errorElement.textContent = '';
            }
        };

        // Fungsi untuk memeriksa semua input yang wajib diisi
        const validateForm = () => {
            let isValid = true;
            requiredFields.forEach(fieldId => {
                const input = document.getElementById(fieldId);
                const fieldName = fieldNames[fieldId];

                // Cek input apakah kosong atau tidak
                if (input && input.value.trim() === '') {
                    showError(fieldId, `${fieldName} wajib diisi.`);
                    isValid = false;
                } else {
                    hideError(fieldId);
                }
            });

            // Validasi format email sederhana
            const emailInput = document.getElementById('receiver_email');
            if (emailInput && emailInput.value.trim() !== '' && !emailInput.value.includes('@')) {
                showError('receiver_email', 'Format email tidak valid.');
                isValid = false;
            }

            // Validasi tanggal
            const paymentDate = document.getElementById('payment_date').value;
            const today = new Date().toISOString().split('T')[0];
            if (paymentDate > today) {
                showError('payment_date', 'Tanggal pembayaran tidak boleh lebih dari hari ini.');
                isValid = false;
            }

            return isValid;
        };

        // Tambahkan event listener untuk setiap input
        requiredFields.forEach(fieldId => {
            const input = document.getElementById(fieldId);
            if (input) {
                input.addEventListener('input', () => {
                    if (input.value.trim() !== '') {
                        hideError(fieldId);
                    }
                });
            }
        });

        // Tangani saat form disubmit
        paymentForm.addEventListener('submit', function(e) {
            // Hapus atribut 'required' agar tidak memicu validasi browser
            requiredFields.forEach(fieldId => {
                const input = document.getElementById(fieldId);
                if (input) {
                    input.removeAttribute('required');
                }
            });

            if (!validateForm()) {
                e.preventDefault();
                // Scroll ke elemen pertama yang tidak valid jika ada
                const firstInvalid = document.querySelector('.is-invalid');
                if (firstInvalid) {
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });

        // Tambahkan atribut novalidate ke form agar browser tidak menjalankan validasi default
        paymentForm.setAttribute('novalidate', '');
    });
</script>
@endsection
