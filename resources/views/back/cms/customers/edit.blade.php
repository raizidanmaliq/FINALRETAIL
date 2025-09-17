@extends('layouts.admin.app')

@section('header')
<header class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Perbaharui Pelanggan</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.cms.customers.index') }}">Pelanggan</a></li>
                <li class="breadcrumb-item active">Perbaharui Pelanggan</li>
            </ol>
        </div>
    </div>
</header>
@endsection

@section('content')
<section class="content">
    <div class="card">
        <div class="card-body">
            <form id="form-validation" action="{{ route('admin.cms.customers.update', $customer) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="name">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $customer->name) }}">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $customer->email) }}">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="phone">Telepon</label>
                        <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $customer->phone) }}">
                        @error('phone')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="address">Alamat</label>
                        <textarea name="address" id="address" class="form-control">{{ old('address', $customer->address) }}</textarea>
                        @error('address')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="password">Password (kosongkan jika tidak ingin diubah)</label>
                        <input type="password" name="password" id="password" class="form-control">
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-danger float-right"
                            style="background-color:#9B4141; border-color:#9B4141; color:#fff;">
                        <i class="la la-check-square-o"></i> Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('#form-validation').validate({
            rules: {
                name: {
                    required: true,
                    minlength: 2
                },
                email: {
                    required: true,
                    email: true
                },
                phone: {
                    digits: true
                },
                // Aturan validasi password hanya jika field diisi
                password: {
                    minlength: 8
                },
                password_confirmation: {
                    equalTo: '#password'
                }
            },
            messages: {
                name: {
                    required: "Nama wajib diisi.",
                    minlength: "Nama minimal 2 karakter."
                },
                email: {
                    required: "Email wajib diisi.",
                    email: "Format email tidak valid."
                },
                phone: {
                    digits: "Nomor telepon harus berupa angka."
                },
                password: {
                    minlength: "Password minimal 8 karakter."
                },
                password_confirmation: {
                    equalTo: "Konfirmasi password tidak sama."
                }
            },
            errorElement: 'small',
            errorClass: 'text-danger',
            errorPlacement: function(error, element) {
                error.insertAfter(element);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
@endpush
