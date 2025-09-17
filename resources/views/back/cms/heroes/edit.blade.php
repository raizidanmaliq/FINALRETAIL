@extends('layouts.admin.app')

@section('header')
<header class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Perbaharui Banner Utama</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.cms.heroes.index') }}">Banner Utama</a>
                </li>
                <li class="breadcrumb-item active">Perbaharui Banner Utama</li>
            </ol>
        </div>
    </div>
</header>
@endsection

@section('content')
<div class="content-body">
    <form id="form-validation" method="POST" action="{{ route('admin.cms.heroes.update', $hero) }}" enctype="multipart/form-data">
        <div class="card">
            <div class="card-body">
                @method('PATCH')
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="headline">Judul</label>
                        <input type="text" name="headline" value="{{ $hero->headline }}" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="subheadline">Sub Judul</label>
                        <input type="text" name="subheadline" value="{{ $hero->subheadline }}" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="images">Gambar Hero</label>
                        <input type="file" name="images[]" class="form-control" multiple>
                        <small class="form-text text-muted">Unggah hingga 3 file untuk mengganti gambar yang ada.</small>
                        @if (!empty($hero->images))
                            <div class="mt-2 d-flex">
                                @foreach($hero->images as $imagePath)
                                    <a href="{{ asset($imagePath) }}" target="_blank" class="btn btn-dark btn-sm mr-2">
                                        <i class="la la-image"></i> Lihat Gambar {{ $loop->iteration }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="form-group col-md-6">
                        <label for="is_active">Status</label>
                        <select name="is_active" class="form-control">
                            <option value="1" {{ $hero->is_active ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ !$hero->is_active ? 'selected' : '' }}>Tidak Aktif</option>
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
                'headline': {
                    required: true,
                },
                'subheadline': {
                    required: true,
                },
                'images[]': {
                    // Validasi hanya berjalan jika ada file yang diunggah
                    extension: "jpg|jpeg|png|gif",
                    maxFiles: 3
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
                    extension: "Format file harus jpg, jpeg, png, atau gif.",
                    maxFiles: "Anda hanya dapat mengunggah maksimal 3 file."
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
