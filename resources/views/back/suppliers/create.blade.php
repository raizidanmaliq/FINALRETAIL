@extends('layouts.admin.app')

@section('header')
    <header class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Tambah Supplier</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.suppliers.index') }}">Supplier</a>
                    </li>
                    <li class="breadcrumb-item active">Tambah Supplier</li>
                </ol>
            </div>
        </div>
    </header>
@endsection

@section('content')
    <div class="content-body">
        <form id="form-validation" method="POST" action="{{ route('admin.suppliers.store') }}">
            <div class="card">
                <div class="card-body">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="name">Nama Supplier <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div id="name-error-js" class="invalid-feedback"></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="email">Email </label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div id="email-error-js" class="invalid-feedback"></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="phone">Telepon</label>
                            <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div id="phone-error-js" class="invalid-feedback"></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="address">Alamat</label>
                            <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div id="address-error-js" class="invalid-feedback"></div>
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
        $('#form-validation').on('submit', function(e) {
            let isValid = true;
            // Hanya validasi field 'name'
            const fields = ['name'];

            fields.forEach(field => {
                $(`#${field}`).removeClass('is-invalid');
                $(`#${field}-error-js`).text('').hide();

                if ($(`#${field}`).val().trim() === '') {
                    isValid = false;
                    $(`#${field}`).addClass('is-invalid');
                    $(`#${field}-error-js`).text(`Field ${field} tidak boleh kosong.`).show();
                }
            });

            if (!isValid) {
                e.preventDefault();
            }
        });

        // Hapus error saat pengguna mulai mengetik
        $('#form-validation input, #form-validation textarea').on('input', function() {
            $(this).removeClass('is-invalid');
            $(`#${this.id}-error-js`).text('').hide();
        });
    });
</script>
@endpush
