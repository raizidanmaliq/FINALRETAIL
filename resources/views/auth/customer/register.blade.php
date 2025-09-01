@extends('layouts.auth.common.app')

@section('content')

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card border rounded-3 shadow-sm w-100" style="max-width: 400px;">
        <div class="card-body p-4">

            {{-- Judul --}}
            <h3 class="text-center fw-bold mb-3" style="color: #A34A4A;">Buat Akun Pelanggan</h3>
            <p class="text-center text-muted mb-4">
                Sudah punya akun?
                <a href="{{ route('customer.auth.login.index') }}"
                   class="text-decoration-none"
                   style="color: #A34A4A; font-weight: 600;">
                    Masuk di sini
                </a>
            </p>

            {{-- Alert success --}}
            @if(session('success'))
                <div class="alert alert-success small">{{ session('success') }}</div>
            @endif

            {{-- Alert error --}}
            @if($errors->any())
                <div class="alert alert-danger small">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form register --}}
            <form action="{{ route('customer.auth.registers.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Nama lengkap" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="nama@email.com" required>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Nomor Telepon</label>
                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="08xxxxxxxxxx" required>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Alamat Lengkap</label>
                    <textarea class="form-control" id="address" name="address" rows="2" placeholder="Alamat tempat tinggal" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
                </div>

                {{-- Tombol --}}
                <div class="d-grid">
                    <button type="submit"
                        class="btn w-100 py-2 fw-semibold rounded-2 shadow-sm"
                        style="background-color: #A34A4A; color: #fff; border: none;">
                        Daftar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
