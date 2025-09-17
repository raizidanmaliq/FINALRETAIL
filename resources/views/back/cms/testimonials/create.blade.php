@extends('layouts.admin.app')

@section('header')
    <header class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Tambah Testimoni Pelanggan</h1>
            </div>

            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.cms.testimonials.index') }}">Testimoni</a>
                    </li>
                    <li class="breadcrumb-item active">Tambah Testimoni Pelanggan</li>
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
                            <label for="customer_name">Nama Pelanggan <span class="text-danger">*</span></label>
                            <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name') }}">
                            @error('customer_name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="rating">Rating <span class="text-danger">*</span></label>
                            <select name="rating" class="form-control">
                                <option value="">-- Pilih Rating --</option>
                                @for ($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>{{ $i }} Bintang</option>
                                @endfor
                            </select>
                            @error('rating') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="order_date">Tanggal Pemesanan <span class="text-danger">*</span></label>
                            <input type="date" name="order_date" class="form-control" value="{{ old('order_date') }}">
                            @error('order_date') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="product_name">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" name="product_name" class="form-control" value="{{ old('product_name') }}">
                            @error('product_name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="customer_photo">Foto Pelanggan <span class="text-danger">*</span></label>
                            <input type="file" name="customer_photo" class="form-control">
                            @error('customer_photo') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group col-md-12">
                            <label for="review">Ulasan <span class="text-danger">*</span></label>
                            <textarea name="review" class="form-control" id="reviewInput" rows="4">{{ old('review') }}</textarea>
                            @error('review') <small class="text-danger">{{ $message }}</small> @enderror
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
            // Inisialisasi Summernote untuk field ulasan
            $('#reviewInput').summernote({
                height: 300,
            });

            // Tambahkan metode validasi kustom untuk Summernote
            $.validator.addMethod("summernoteRequired", function(value, element) {
                const summernoteContent = $('#reviewInput').summernote('isEmpty');
                return !summernoteContent;
            }, "Ulasan wajib diisi.");

            // Konfigurasi jQuery Validation
            $('#form-validation').validate({
                ignore: [], // Memastikan validator tidak mengabaikan field yang hidden
                rules: {
                    customer_name: {
                        required: true,
                        minlength: 2, // Contoh: minimal 2 karakter
                    },
                    rating: {
                        required: true,
                        digits: true, // Hanya menerima angka
                        range: [1, 5], // Memastikan angka antara 1 dan 5
                    },
                    order_date: {
                        required: true,
                        date: true, // Memastikan formatnya adalah tanggal
                    },
                    product_name: {
                        required: true,
                    },
                    customer_photo: {
                        required: true,
                        // Aturan tambahan untuk tipe file bisa ditambahkan di sini
                    },
                    review: {
                        summernoteRequired: true, // Gunakan aturan validasi kustom
                    },
                },
                messages: {
                    customer_name: {
                        required: "Nama pelanggan wajib diisi.",
                        minlength: "Nama pelanggan minimal 2 karakter."
                    },
                    rating: {
                        required: "Rating wajib dipilih.",
                        digits: "Rating harus berupa angka.",
                        range: "Rating harus antara 1 dan 5."
                    },
                    order_date: {
                        required: "Tanggal pemesanan wajib diisi.",
                        date: "Tanggal pemesanan tidak valid."
                    },
                    product_name: {
                        required: "Nama produk wajib diisi."
                    },
                    customer_photo: {
                        required: "Foto pelanggan wajib diunggah."
                    },
                    review: {
                        summernoteRequired: "Ulasan wajib diisi."
                    },
                },
                errorElement: 'small',
                errorClass: 'text-danger',
                errorPlacement: function(error, element) {
                    if (element.attr("id") === "reviewInput") {
                        error.insertAfter(element.siblings('.note-editor'));
                    } else {
                        error.insertAfter(element);
                    }
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
