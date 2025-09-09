@extends('layouts.admin.app')

@section('header')
    <header class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Perbaharui Kategori</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.cms.product_categories.index') }}">Kategori Produk</a>
                    </li>
                    <li class="breadcrumb-item active">Perbaharui Kategori</li>
                </ol>
            </div>
        </div>
    </header>
@endsection

@section('content')
    <div class="content-body">
        <form method="POST" action="{{ route('admin.cms.product_categories.update', $productCategory) }}">
            <div class="card">
                <div class="card-body">
                    @method('PATCH')
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="name">Nama Kategori</label>
                            <input type="text" name="name" value="{{ $productCategory->name }}" class="form-control" required>
                            @error('name')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
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
        $('#form-validation').validate({
            rules: {},
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
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
