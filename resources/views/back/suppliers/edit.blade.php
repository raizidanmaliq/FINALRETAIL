@extends('layouts.admin.app')

@section('header')
    <header class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Perbaharui Supplier</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.suppliers.index') }}">Supplier</a>
                    </li>
                    <li class="breadcrumb-item active">Perbaharui Supplier</li>
                </ol>
            </div>
        </div>
    </header>
@endsection

@section('content')
    <div class="content-body">
        <form id="form-update-supplier" method="POST" action="{{ route('admin.suppliers.update', $supplier) }}">
            <div class="card">
                <div class="card-body">
                    @method('PATCH')
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="name">Nama Supplier <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name"
                                value="{{ old('name', $supplier->name) }}"
                                class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div id="name-error-js" class="invalid-feedback"></div>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email"
                                value="{{ old('email', $supplier->email) }}"
                                class="form-control @error('email') is-invalid @enderror">
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div id="email-error-js" class="invalid-feedback"></div>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="phone">Telepon</label>
                            <input type="text" name="phone" id="phone"
                                value="{{ old('phone', $supplier->phone) }}"
                                class="form-control @error('phone') is-invalid @enderror">
                            @error('phone')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div id="phone-error-js" class="invalid-feedback"></div>
                        </div>

                        <div class="form-group col-md-12">
                            <label for="address">Alamat</label>
                            <textarea name="address" id="address"
                                class="form-control @error('address') is-invalid @enderror">{{ old('address', $supplier->address) }}</textarea>
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
        $('#form-update-supplier').on('submit', function(e) {
            let isValid = true;
            // hanya validasi 'name' (sama kayak create)
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

        // hapus error pas user ketik
        $('#form-update-supplier input, #form-update-supplier textarea').on('input', function() {
            $(this).removeClass('is-invalid');
            $(`#${this.id}-error-js`).text('').hide();
        });
    });
</script>
@endpush
