@extends('layouts.auth.common.app')

@push('css')
<style>
    body {
        font-family: 'Poppins', sans-serif !important;
    }
</style>
@endpush

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card border rounded-3 shadow-sm w-100" style="max-width: 400px;">
        <div class="card-body p-4">

            {{-- 🔼 Logo / Brand (center) --}}
            <div class="text-center mb-4">
                <h4 class="fw-bold mb-1" style="color:#A34A4A;">Ahlinya Retail</h4>
                <p class="text-muted mb-0" style="font-size: 0.9rem;">
                    Masuk ke akun Anda
                </p>
            </div>

            {{-- ✅ Pesan berhasil register --}}
            @if(session('registered'))
                <div class="alert alert-success small text-center mb-4">
                    <i class="fa fa-check-circle me-1"></i>
                    Berhasil register, silakan login.
                </div>
            @endif

            {{-- Form login --}}
            <form action="{{ route('customer.auth.login.authentication') }}" method="POST" class="text-start">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label fw-medium">Alamat Email</label>
                    <input type="email" class="form-control" id="email" name="email"
                           placeholder="nama@email.com" required>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label fw-medium">Kata Sandi</label>
                    <input type="password" class="form-control" id="password" name="password"
                           placeholder="Masukan Password" required>
                </div>

                <button type="submit" class="btn w-100 py-2" style="background:#A34A4A; color:white;">
                    Masuk
                </button>
            </form>

            {{-- 🔽 Link daftar --}}
            <p class="text-center mt-4 mb-0">
                Belum punya akun?
                <a href="{{ route('customer.auth.registers.index') }}" style="color:#A34A4A; font-weight:500;">
                    Daftar di sini
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
