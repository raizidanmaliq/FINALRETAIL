@extends('layouts.admin.app')

@section('header')
    <header class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Perbaharui Testimoni</h1>
            </div>

            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.cms.testimonials.index') }}">Testimoni</a>
                    </li>
                    <li class="breadcrumb-item active">Perbaharui Testimoni</li>
                </ol>
            </div>
        </div>
    </header>
@endsection

@section('content')
    <div class="content-body">
        <form method="POST" action="{{ route('admin.cms.testimonials.update', $testimonial) }}" enctype="multipart/form-data">
            <div class="card">
                <div class="card-body">
                    @method('PATCH')
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="customer_name">Nama Pelanggan</label>
                            <input type="text" name="customer_name" value="{{ $testimonial->customer_name }}" class="form-control" required>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="rating">Rating</label>
                            <select name="rating" class="form-control" required>
                                @for ($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}" {{ $testimonial->rating == $i ? 'selected' : '' }}>{{ $i }} Bintang</option>
                                @endfor
                            </select>
                        </div>

                        {{-- Tambahan --}}
                        <div class="form-group col-md-6">
                            <label for="order_date">Tanggal Pemesanan</label>
                            <input type="date" name="order_date" value="{{ $testimonial->order_date }}" class="form-control">
                        </div>

                        {{-- Tambahan --}}
                        <div class="form-group col-md-6">
                            <label for="product_name">Nama Produk</label>
                            <input type="text" name="product_name" value="{{ $testimonial->product_name }}" class="form-control">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="customer_photo">Foto Pelanggan</label>
                            <input type="file" name="customer_photo" class="form-control">
                            @if ($testimonial->customer_photo)
                                <a href="{{ asset($testimonial->customer_photo) }}" target="_blank" class="btn btn-sm btn-primary mt-2">Lihat Foto</a>
                            @endif
                        </div>

                        <div class="form-group col-md-12">
                            <label for="review">Ulasan</label>
                            <textarea name="review" class="form-control" id="reviewInput" rows="4">{{ $testimonial->review }}</textarea>
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
            $('#reviewInput').summernote({
                height: 300,
            })
        })
    </script>
@endpush
