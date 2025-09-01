@extends('layouts.admin.app')

@section('header')
    <header class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Tambah Banner Promo</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.cms.banners.index') }}">Banner Promo</a>
                    </li>
                    <li class="breadcrumb-item active">Tambah Banner</li>
                </ol>
            </div>
        </div>
    </header>
@endsection

@section('content')
    <div class="content-body">
        <form id="form-validation" method="POST" action="{{ route('admin.cms.banners.store') }}" enctype="multipart/form-data">
            <div class="card">
                <div class="card-body">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="title">Judul Banner (Opsional)</label>
                            <input type="text" name="title" class="form-control">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="link">Link Tujuan (Opsional)</label>
                            <input type="url" name="link" class="form-control" placeholder="Contoh: https://contoh.com/promo">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="image">Gambar Banner</label>
                            <input type="file" name="image" class="form-control" required>
                            <small class="form-text text-muted">Disarankan: Ukuran 1920x600 px</small>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="is_active">Status</label>
                            <select name="is_active" class="form-control">
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-success float-right" style="background-color: #9B4141;>
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
            rules: {
                image: {
                    required: true,
                    extension: "jpg|jpeg|png|gif"
                }
            },
            messages: {
                image: {
                    required: "Gambar banner harus diunggah.",
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
