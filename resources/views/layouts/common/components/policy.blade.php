@extends('layouts.common.app')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <h1 class="header-title mb-4 text-center">Kebijakan Privasi & Syarat dan Ketentuan</h1>

            {{-- Bagian Kebijakan Privasi --}}
            <h2 class="mt-5 mb-3">Privacy Policy</h2>
            <div class="card p-4 shadow-sm">
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                </p>
                <p>
                    Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                </p>
            </div>

            {{-- Bagian Syarat & Ketentuan --}}
            <h2 class="mt-5 mb-3">Syarat & Ketentuan</h2>
            <div class="card p-4 shadow-sm">
                <p>
                    Ini adalah syarat dan ketentuan penggunaan layanan kami. Dengan menggunakan website ini, Anda dianggap telah menyetujui seluruh ketentuan yang berlaku.
                </p>
                <ul>
                    <li>Penggunaan situs harus sesuai dengan hukum yang berlaku.</li>
                    <li>Konten yang Anda unggah menjadi tanggung jawab Anda sepenuhnya.</li>
                    <li>Kami berhak mengubah syarat dan ketentuan kapan saja.</li>
                </ul>
            </div>

        </div>
    </div>
</div>
@endsection
