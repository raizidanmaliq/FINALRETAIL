@extends('layouts.admin.app')

@section('header')
<header class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Perbaharui CTA</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.cms.ctas.index') }}">CTA</a>
                </li>
                <li class="breadcrumb-item active">Perbaharui CTA</li>
            </ol>
        </div>
    </div>
</header>
@endsection

@section('content')
<div class="content-body">
    <form id="form-validation" method="POST" action="{{ route('admin.cms.ctas.update', $cta) }}" enctype="multipart/form-data">
        <div class="card">
            <div class="card-body">
                @method('PATCH')
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="title">Judul CTA</label>
                        <input type="text" name="title" value="{{ $cta->title }}" class="form-control" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="image">Gambar CTA</label>
                        <input type="file" name="image" class="form-control">
                        <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah gambar.</small>
                        @if ($cta->image)
                            <a href="{{ asset($cta->image) }}" target="_blank" class="btn btn-dark btn-sm mt-2">
                                <i class="la la-image"></i> Lihat Gambar Saat Ini
                            </a>
                        @endif
                    </div>

                    <div class="form-group col-md-6">
                        <label for="is_active">Status</label>
                        <select name="is_active" class="form-control">
                            <option value="1" {{ $cta->is_active ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ !$cta->is_active ? 'selected' : '' }}>Tidak Aktif</option>
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
        $('#form-validation').validate({
            rules: {
                title: {
                    required: true
                },
                image: {
                    extension: "jpg|jpeg|png|gif"
                }
            },
            messages: {
                title: {
                    required: "Judul CTA harus diisi."
                },
                image: {
                    extension: "Format file harus jpg, jpeg, png, atau gif."
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
