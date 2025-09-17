@extends('layouts.admin.app')

@section('header')
    <header class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Perbaharui Banner Promo</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.cms.banners.index') }}">Banner Promo</a>
                    </li>
                    <li class="breadcrumb-item active">Perbaharui Banner Promo</li>
                </ol>
            </div>
        </div>
    </header>
@endsection

@section('content')
    <div class="content-body">
        <form method="POST" action="{{ route('admin.cms.banners.update', $banner) }}" enctype="multipart/form-data">
            <div class="card">
                <div class="card-body">
                    @method('PATCH')
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="title">Judul Banner (Opsional)</label>
                            <input type="text" name="title" value="{{ $banner->title }}" class="form-control">
                        </div>



                        <div class="form-group col-md-6">
                            <label for="image">Gambar Banner</label>
                            <input type="file" name="image" class="form-control">
                            <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah gambar.</small>
                            @if ($banner->image)
                                <a href="{{ asset($banner->image) }}" target="_blank"
   class="btn btn-dark btn-sm mt-2">
    <i class="la la-image"></i> Lihat Gambar Saat Ini
</a>

                            @endif
                        </div>

                        <div class="form-group col-md-6">
                            <label for="is_active">Status</label>
                            <select name="is_active" class="form-control">
                                <option value="1" {{ $banner->is_active ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ !$banner->is_active ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-danger float-right"
        style="background-color: #9B4141; border-color: #9B4141; color:#fff;">
    <i class="la la-check-square-o"></i> Simpan Perubahan
</button>

                </div>
            </div>
        </form>
    </div>
@endsection
