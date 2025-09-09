@extends('layouts.admin.app')

@section('header')
    <header class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Tambah Testimoni</h1>
            </div>

            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.cms.testimonials.index') }}">Testimoni</a>
                    </li>
                    <li class="breadcrumb-item active">Tambah Testimoni</li>
                </ol>
            </div>
        </div>
    </header>
@endsection

@section('content')
    <div class="content-body">
        <form id="form-validation" method="POST" action="{{ route('admin.cms.testimonials.store') }}" enctype="multipart/form-data">
            <div class="card">
                <div class="card-body">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="customer_name">Nama Pelanggan</label>
                            <input type="text" name="customer_name" class="form-control" required>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="rating">Rating</label>
                            <select name="rating" class="form-control" required>
                                @for ($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}">{{ $i }} Bintang</option>
                                @endfor
                            </select>
                        </div>

                        {{-- Tambahan --}}
                        <div class="form-group col-md-6">
                            <label for="order_date">Tanggal Pemesanan</label>
                            <input type="date" name="order_date" class="form-control">
                        </div>

                        {{-- Tambahan --}}
                        <div class="form-group col-md-6">
                            <label for="product_name">Nama Produk</label>
                            <input type="text" name="product_name" class="form-control">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="customer_photo">Foto Pelanggan</label>
                            <input type="file" name="customer_photo" class="form-control">
                        </div>

                        <div class="form-group col-md-12">
                            <label for="review">Ulasan</label>
                            <textarea name="review" class="form-control" id="reviewInput" rows="4" required></textarea>
                        </div>
                    </div>
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
            })

            $('#reviewInput').summernote({
                height: 300,
            })
        })
    </script>
@endpush
