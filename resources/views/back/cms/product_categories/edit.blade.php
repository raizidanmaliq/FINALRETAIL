@extends('layouts.admin.app')

@section('header')
    <header class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Perbaharui Kategori Produk</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.cms.product_categories.index') }}">Kategori Produk</a>
                    </li>
                    <li class="breadcrumb-item active">Perbaharui Kategori Produk</li>
                </ol>
            </div>
        </div>
    </header>
@endsection

@section('content')
    <div class="content-body">
        <form method="POST" action="{{ route('admin.cms.product_categories.update', $productCategory) }}" id="form-update-category">
            <div class="card">
                <div class="card-body">
                    @method('PATCH')
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="name">Nama Kategori <span class="text-danger">*</span></label>
                            {{-- Hapus atribut required untuk validasi JS terpusat --}}
                            <input type="text" name="name" id="name" value="{{ old('name', $productCategory->name) }}" class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            {{-- Tempatkan div ini untuk pesan error dari JavaScript --}}
                            <div id="name-error-js" class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-danger float-right"
                            style="background-color:#9B4141; border-color:#9B4141; color:#fff;">
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
        // Logika validasi sisi klien
        $('#form-update-category').on('submit', function(e) {
            let isValid = true;
            const nameInput = $('#name');

            // Hapus semua error sebelumnya
            nameInput.removeClass('is-invalid');
            $('#name-error-js').text('').hide();

            // Validasi field nama
            if (nameInput.val().trim() === '') {
                isValid = false;
                nameInput.addClass('is-invalid');
                $('#name-error-js').text('Nama kategori tidak boleh kosong.').show();
            }

            if (!isValid) {
                e.preventDefault();
                // Opsional: scroll ke field yang bermasalah
                $([document.documentElement, document.body]).animate({
                    scrollTop: nameInput.offset().top - 100
                }, 500);
            }
        });

        // Hapus error saat pengguna mulai mengetik
        $('#name').on('input', function() {
            $(this).removeClass('is-invalid');
            $('#name-error-js').text('').hide();
        });
    });
</script>
@endpush
