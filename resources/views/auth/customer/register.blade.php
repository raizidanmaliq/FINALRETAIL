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

            {{-- 🔼 Heading --}}
            <div class="text-center mb-4">
                <h3 class="fw-bold mb-1" style="color:#A34A4A;">Daftar Akun</h3>
                <p class="text-muted mb-0" style="font-size: 0.9rem;">
                    Lengkapi data berikut untuk mendaftar
                </p>
            </div>

            {{-- Form register --}}
            <form action="{{ route('customer.auth.registers.store') }}" method="POST" class="text-start">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="name" name="name"
                           placeholder="Nama lengkap" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input type="email" class="form-control" id="email" name="email"
                           placeholder="nama@email.com" value="{{ old('email') }}" required>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Nomor Telepon</label>
                    <input type="tel" class="form-control" id="phone" name="phone"
                           placeholder="08xxxxxxxxxx" value="{{ old('phone') }}" required>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Alamat Lengkap</label>
                    <textarea class="form-control" id="address" name="address" rows="2"
                              placeholder="Alamat tempat tinggal" required>{{ old('address') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <input type="password" class="form-control" id="password" name="password"
                           placeholder="Masukan Password" required>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                    <input type="password" class="form-control" id="password_confirmation"
                           name="password_confirmation" placeholder="Konfirmasi Password" required>
                </div>

                <div class="d-grid">
                    <button type="submit"
                        class="btn w-100 py-2 fw-semibold rounded-2 shadow-sm"
                        style="background-color: #A34A4A; color: #fff; border: none;">
                        Daftar
                    </button>
                </div>
            </form>

            {{-- 🔽 Link ke login --}}
            <p class="text-center mt-4 mb-0">
                Sudah punya akun?
                <a href="{{ route('customer.auth.login.index') }}" style="color:#A34A4A; font-weight:600;">
                    Masuk di sini
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
