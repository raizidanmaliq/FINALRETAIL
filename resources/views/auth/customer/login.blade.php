@extends('layouts.auth.common.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card border rounded-3 shadow-sm w-100" style="max-width: 400px;">
        <div class="card-body p-4">

            {{-- Judul --}}
            <h3 class="text-center fw-bold mb-3" style="color:#A34A4A;">Masuk Akun</h3>
            <p class="text-center mb-4">
                Belum punya akun?
                <a href="{{ route('customer.auth.registers.index') }}" style="color:#A34A4A; font-weight:500;">
                    Daftar di sini
                </a>
            </p>

            @if(session('success'))
            <div class="alert alert-success small">{{ session('success') }}</div>
            @endif

            {{-- Form login --}}
            <form action="{{ route('customer.auth.login.authentication') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="nama@email.com" required>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn w-100" style="background:#A34A4A; color:white;">
                    Masuk
                </button>
            </form>

        </div>
    </div>
</div>
@endsection
