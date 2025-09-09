@extends('layouts.admin.app')

@section('header')
    <header class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                @php
                    $titleMap = [
                        'privacy-policy' => 'Kebijakan Privasi',
                        'terms-and-conditions' => 'Syarat & Ketentuan',
                        'general-settings' => 'Pengaturan Umum'
                    ];
                    $pageTitle = $titleMap[$informationPage->slug] ?? 'Halaman Informasi';
                @endphp
                <h1>Perbaharui {{ $pageTitle }}</h1>
            </div>

            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.cms.information-pages.index') }}">Manajemen Halaman</a>
                    </li>
                    <li class="breadcrumb-item active">{{ $pageTitle }}</li>
                </ol>
            </div>
        </div>
    </header>
@endsection

@section('content')
    <div class="content-body">
        <form method="POST" action="{{ route('admin.cms.information-pages.update', $informationPage->slug) }}" id="form-validation">
            <div class="card">
                <div class="card-body">
                    @method('PUT')
                    @csrf

                    @if ($informationPage->slug !== 'general-settings')
                        {{-- Form untuk halaman informasi biasa --}}
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="title">Judul Halaman</label>
                                <input type="text" name="title" value="{{ $informationPage->title }}" class="form-control" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="content">Konten</label>
                                <textarea name="content" class="form-control" id="contentInput" rows="10" required>{{ $informationPage->content }}</textarea>
                            </div>
                        </div>
                    @else
                        {{-- Form khusus untuk Pengaturan Umum --}}
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="company_name">Nama Toko</label>
                                <input type="text" name="company_name" value="{{ $informationPage->company_name }}" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="company_tagline">Slogan</label>
                                <input type="text" name="company_tagline" value="{{ $informationPage->company_tagline }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="whatsapp">WhatsApp</label>
                                <input type="text" name="whatsapp" value="{{ $informationPage->whatsapp }}" class="form-control">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="email">Email</label>
                                <input type="email" name="email" value="{{ $informationPage->email }}" class="form-control">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="address">Alamat</label>
                                <input type="text" name="address" value="{{ $informationPage->address }}" class="form-control">
                            </div>
                        </div>
                        <hr>
                        <h5>Tautan Media Sosial</h5>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="facebook_url">Facebook URL</label>
                                <input type="url" name="facebook_url" value="{{ $informationPage->facebook_url }}" class="form-control">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="instagram_url">Instagram URL</label>
                                <input type="url" name="instagram_url" value="{{ $informationPage->instagram_url }}" class="form-control">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="tiktok_url">TikTok URL</label>
                                <input type="url" name="tiktok_url" value="{{ $informationPage->tiktok_url }}" class="form-control">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="youtube_url">YouTube URL</label>
                                <input type="url" name="youtube_url" value="{{ $informationPage->youtube_url }}" class="form-control">
                            </div>
                        </div>
                    @endif
                </div>
                <div class="card-footer">
    <button type="submit" class="btn btn-danger float-right"
            style="background-color: #9B4141; border-color: #9B4141; color:#fff;">
        <i class="la la-check-square-o"></i> Simpan
    </button>
</div>

            </div>
        </form>
    </div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        @if ($informationPage->slug !== 'general-settings')
            $('#contentInput').summernote({ height: 300 });
        @endif
    });
</script>
@endpush
