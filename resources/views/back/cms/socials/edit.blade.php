@extends('layouts.admin.app')

@section('header')
<header class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Perbaharui Sosial & E-commerce</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.cms.socials.index') }}">Sosial & E-commerce</a>
                </li>
                <li class="breadcrumb-item active">Perbaharui Sosial & E-commerce</li>
            </ol>
        </div>
    </div>
</header>
@endsection

@section('content')
<div class="content-body">
    <form id="form-validation" method="POST" action="{{ route('admin.cms.socials.update', $social) }}" enctype="multipart/form-data">
        <div class="card">
            <div class="card-body">
                @method('PATCH')
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="images">Gambar</label>
                        <input type="file" name="images[]" class="form-control" multiple>
                        <small class="form-text text-muted">Unggah hingga 3 file untuk mengganti gambar yang ada.</small>
                        @if (!empty($social->images))
                            <div class="mt-2 d-flex">
                                @foreach($social->images as $imagePath)
                                    <a href="{{ asset($imagePath) }}" target="_blank" class="btn btn-dark btn-sm mr-2">
                                        <i class="la la-image"></i> Lihat Gambar {{ $loop->iteration }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="form-group col-md-6">
                        <label for="button_text">Tombol Media Sosial</label>
                        <input type="text" name="button_text" value="{{ $social->button_text }}" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="button_link">Tautan Media Sosial</label>
                        <input type="url" name="button_link" value="{{ $social->button_link }}" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="shopee_link">Tautan Shopee</label>
                        <input type="url" name="shopee_link" value="{{ $social->shopee_link }}" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="tokopedia_link">Tautan Tokopedia</label>
                        <input type="url" name="tokopedia_link" value="{{ $social->tokopedia_link }}" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="lazada_link">Tautan Lazada</label>
                        <input type="url" name="lazada_link" value="{{ $social->lazada_link }}" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="is_active">Status</label>
                        <select name="is_active" class="form-control">
                            <option value="1" {{ $social->is_active ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ !$social->is_active ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-danger float-right" style="background-color: #9B4141; border-color: #9B4141; color:#fff;">
                    <i class="la la-check-square-o"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        // Menambahkan metode validasi kustom untuk jumlah file
        $.validator.addMethod('maxFiles', function(value, element, param) {
            if (element.files.length > param) {
                return false;
            }
            return true;
        }, 'Anda hanya dapat mengunggah maksimal {0} file.');

        $('#form-validation').validate({
            rules: {
                'images[]': {
                    extension: "jpg|jpeg|png|gif",
                    maxFiles: 3
                },
                'button_text': {
                    required: true
                },
                'button_link': {
                    required: true,
                    url: true // Memastikan input adalah URL yang valid
                },
                'is_active': {
                    required: true,
                }
            },
            messages: {
                'images[]': {
                    extension: "Format file harus jpg, jpeg, png, atau gif.",
                    maxFiles: "Anda hanya dapat mengunggah maksimal 3 file."
                },
                'button_text': {
                    required: "Teks Tombol harus diisi."
                },
                'button_link': {
                    required: "Tautan Tombol harus diisi.",
                    url: "Masukkan URL yang valid (misal: https://instagram.com/akun-anda)."
                },
                'is_active': {
                    required: "Status harus dipilih.",
                }
            },
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
