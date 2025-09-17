@extends('layouts.admin.app')

@section('header')
    <header class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Manajemen Halaman Informasi</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Manajemen Halaman Informasi</li>
                </ol>
            </div>
        </div>
    </header>
@endsection

@section('content')
    <div class="content-body">
        <div class="row">
            {{-- Kartu untuk Kebijakan Privasi --}}
            <div class="col-md-4">
                <div class="card card-success card-outline">
                    <div class="card-header">
                        <h5 class="card-title">Kebijakan Privasi</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Kelola kebijakan dan pernyataan privasi website.</p>
                        <a href="{{ route('admin.cms.information-pages.edit', 'privacy-policy') }} " class="btn btn-primary";">Edit Halaman</a>
                    </div>
                </div>
            </div>
            {{-- Kartu untuk Syarat & Ketentuan --}}
            <div class="col-md-4">
                <div class="card card-danger card-outline">
                    <div class="card-header">
                        <h5 class="card-title">Syarat & Ketentuan</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Kelola syarat dan ketentuan penggunaan layanan.</p>
                        <a href="{{ route('admin.cms.information-pages.edit', 'terms-and-conditions') }}" class="btn btn-primary">Edit Halaman</a>
                    </div>
                </div>
            </div>

            {{-- Kartu untuk Pengaturan Umum --}}
            <div class="col-md-4">
                <div class="card card-dark card-outline">
                    <div class="card-header">
                        <h5 class="card-title">Pengaturan Umum</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Kelola data umum seperti nama toko, kontak, dan tautan media sosial.</p>
                        <a href="{{ route('admin.cms.information-pages.edit', 'general-settings') }}" class="btn btn-primary">Edit Pengaturan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
