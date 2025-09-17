@extends('layouts.admin.app')

@section('header')
<header class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Tambah Banner Utama</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.cms.heroes.index') }}">Banner Utama</a>
                </li>
                <li class="breadcrumb-item active">Tambah Banner Utamas</li>
            </ol>
        </div>
    </div>
</header>
@endsection

@section('content')
<div class="content-body">
    <form id="form-validation" method="POST" action="{{ route('admin.cms.heroes.store') }}" enctype="multipart/form-data">
        <div class="card">
            <div class="card-body">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="headline">Judul</label>
                        <input type="text" name="headline" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="subheadline">Sub Judul</label>
                        <input type="text" name="subheadline" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="images">Gambar Hero</label>
                        <input type="file" name="images[]" class="form-control" multiple>
                        <small class="form-text text-muted">Maksimal 3 file sekaligus. Disarankan: Ukuran 1920x600 px</small>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="is_active">Status</label>
                        <select name="is_active" class="form-control">
                            <option value="">Pilih Status</option>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success float-right" style="background-color: #9B4141;">
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
        // Menambahkan metode validasi kustom untuk jumlah file
        $.validator.addMethod('maxFiles', function(value, element, param) {
            if (element.files.length > param) {
                return false;
            }
            return true;
        }, 'Anda hanya dapat mengunggah maksimal {0} file.');

        $('#form-validation').validate({
            rules: {
                'headline': {
                    required: true,
                },
                'subheadline': {
                    required: true,
                },
                'images[]': {
                    required: true,
                    extension: "jpg|jpeg|png|gif",
                    maxFiles: 3 // Menggunakan aturan baru untuk membatasi 3 file
                },
                'is_active': {
                    required: true,
                }
            },
            messages: {
                'headline': {
                    required: "Headline harus diisi.",
                },
                'subheadline': {
                    required: "Subheadline harus diisi.",
                },
                'images[]': {
                    required: "Gambar hero harus diunggah.",
                    extension: "Format file harus jpg, jpeg, png, atau gif.",
                    maxFiles: "Anda hanya dapat mengunggah maksimal 3 file." // Pesan kesalahan kustom
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
