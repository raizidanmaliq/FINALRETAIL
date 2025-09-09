@extends('layouts.admin.app')

@section('header')
<header class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Tambah Produk Baru</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.inventory.dashboard') }}">Stok</a>
                </li>
                <li class="breadcrumb-item active">Tambah Produk</li>
            </ol>
        </div>
    </div>
</header>
@endsection

@section('content')
<div class="content-body">
    <form method="POST" action="{{ route('admin.inventory.products.store') }}" enctype="multipart/form-data" id="form-product">
        @csrf
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="form-row">
                    {{-- Nama Produk --}}
                    <div class="form-group col-md-6">
                        <label for="name">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- SKU --}}
                    <div class="form-group col-md-6">
                        <label for="sku">SKU / Barcode <span class="text-danger">*</span></label>
                        <input type="text" name="sku" class="form-control" value="{{ old('sku') }}" required>
                        @error('sku') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="form-row">
                    {{-- Kategori --}}
                    <div class="form-group col-md-6">
                        <label for="category_id">Kategori <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected':'' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- Jenis Produk --}}
                    <div class="form-group col-md-6">
                        <label for="gender">Jenis Produk <span class="text-danger">*</span></label>
                        <select name="gender" class="form-control" required>
                            <option value="">-- Pilih Jenis --</option>
                            @foreach($genders as $gender)
                                <option value="{{ $gender }}" {{ old('gender') == $gender ? 'selected' : '' }}>
                                    {{ $gender }}
                                </option>
                            @endforeach
                        </select>
                        @error('gender') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="form-row">
                    {{-- Satuan --}}
                    <div class="form-group col-md-6">
                        <label for="unit">Satuan <span class="text-danger">*</span></label>
                        <input type="text" name="unit" class="form-control" value="{{ old('unit') }}" required>
                        @error('unit') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- Harga Modal --}}
                    <div class="form-group col-md-6">
                        <label for="cost_price">Harga Modal <span class="text-danger">*</span></label>
                        <input type="number" name="cost_price" class="form-control" value="{{ old('cost_price') }}" required>
                        @error('cost_price') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="form-row">
                    {{-- Harga Jual --}}
                    <div class="form-group col-md-6">
                        <label for="selling_price">Harga Jual <span class="text-danger">*</span></label>
                        <input type="number" name="selling_price" class="form-control" value="{{ old('selling_price') }}" required>
                        @error('selling_price') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- Stok Awal --}}
                    <div class="form-group col-md-6">
                        <label for="inventory">Stok Awal <span class="text-danger">*</span></label>
                        <input type="number" name="inventory" class="form-control" value="{{ old('inventory') }}" required>
                        @error('inventory') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="form-row">
                    {{-- Warna --}}
                    <div class="form-group col-md-6">
                        <label for="colorInput">Warna <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" id="colorInput" class="form-control" placeholder="Masukkan warna">
                            <div class="input-group-append">
                                <button type="button" id="addColorBtn" class="btn btn-dark btn-sm">Tambah</button>
                            </div>
                        </div>
                        <small id="color-error" class="text-danger d-none">Warna sudah ada!</small>
                        <div id="colorList" class="mt-2"></div>
                    </div>

                    {{-- Ukuran --}}
                    <div class="form-group col-md-6">
                        <label for="sizeInput">Ukuran <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" id="sizeInput" class="form-control" placeholder="Masukkan ukuran">
                            <div class="input-group-append">
                                <button type="button" id="addSizeBtn" class="btn btn-dark btn-sm">Tambah</button>
                            </div>
                        </div>
                        <small id="size-error" class="text-danger d-none">Ukuran sudah ada!</small>
                        <div id="sizeList" class="mt-2"></div>
                    </div>
                </div>

                <div class="form-row">
                    {{-- Foto Produk (WAJIB) --}}
                    <div class="form-group col-md-6">
                        <label for="images">Foto Produk (Bisa banyak) <span class="text-danger">*</span></label>
                        <input type="file" name="images[]" class="form-control" multiple required>
                        @error('images.*') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- Size Chart (Opsional) --}}
                    <div class="form-group col-md-6">
                        <label for="size_chart_image">Gambar Size Chart (Opsional) </label>
                        <input type="file" name="size_chart_image" class="form-control">
                        @error('size_chart_image') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                {{-- Detail Ukuran (Opsional) --}}
                <div class="form-group">
                    <label for="size_details">Detail Ukuran (Opsional)</label>
                    <textarea name="size_details" id="sizeDetailsInput" class="form-control" rows="5">{{ old('size_details') }}</textarea>
                    @error('size_details') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Deskripsi (WAJIB) --}}
                <div class="form-group">
                    <label for="description">Deskripsi <span class="text-danger">*</span></label>
                    <textarea name="description" id="descriptionInput" class="form-control" rows="6" required>{{ old('description') }}</textarea>
                    @error('description') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Label Promo (Opsional) --}}
                <div class="form-group">
                    <label for="promo_label">Label Promo (Opsional)</label>
                    <select name="promo_label" class="form-control">
                        <option value="">-- Tidak Ada --</option>
                        <option value="Bestseller" {{ old('promo_label') == 'Bestseller' ? 'selected' : '' }}>Bestseller</option>
                        <option value="Limited Stock" {{ old('promo_label') == 'Limited Stock' ? 'selected' : '' }}>Limited Stock</option>
                        <option value="New Arrival" {{ old('promo_label') == 'New Arrival' ? 'selected' : '' }}>New Arrival</option>
                        <option value="Flash Sale" {{ old('promo_label') == 'Flash Sale' ? 'selected' : '' }}>Flash Sale</option>
                    </select>
                    @error('promo_label') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="card-footer text-right">
    <button type="submit" class="btn btn-danger float-right"
            style="background-color: #9B4141; border-color: #9B4141; color:#fff;">
        <i class="la la-check-square-o"></i> Simpan
    </button>
</div>

    </form>
</div>
@endsection

@push('js')
<script>
$(document).ready(function () {
    // Summernote
    $('#descriptionInput').summernote({ height: 300 });
    $('#sizeDetailsInput').summernote({ height: 200 });

    // Preview banyak gambar
    $('input[name="images[]"]').on('change', function () {
        $('#preview-images').remove();
        let container = $('<div id="preview-images" class="mt-2 d-flex flex-wrap"></div>');
        $(this).after(container);

        Array.from(this.files).forEach(file => {
            let reader = new FileReader();
            reader.onload = (e) => {
                container.append(`<img src="${e.target.result}" class="img-thumbnail m-1" style="width:100px;height:100px;object-fit:cover;">`);
            }
            reader.readAsDataURL(file);
        });
    });

    // Preview size chart
    $('input[name="size_chart_image"]').on('change', function () {
        $('#preview-size-chart').remove();
        let container = $('<div id="preview-size-chart" class="mt-2"></div>');
        $(this).after(container);

        let reader = new FileReader();
        reader.onload = (e) => {
            container.append(`<img src="${e.target.result}" class="img-thumbnail" style="width:200px;object-fit:contain;">`);
        }
        reader.readAsDataURL(this.files[0]);
    });

    // Tambah warna
    $('#addColorBtn').click(function () {
        var color = $('#colorInput').val().trim();
        if (color === '') return;

        var exists = false;
        $('#colorList input').each(function () {
            if ($(this).val().toLowerCase() === color.toLowerCase()) {
                exists = true;
            }
        });

        if (!exists) {
            var newColor = `
                <div class="input-group mb-2">
                    <input type="text" name="colors[]" value="${color}" class="form-control" readonly required>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-danger btn-sm">Hapus</button>
                    </div>
                </div>
            `;
            $('#colorList').append(newColor);
            $('#colorInput').val('');
            $('#color-error').addClass('d-none');
        } else {
            $('#color-error').removeClass('d-none');
        }
    });

    // Tambah ukuran
    $('#addSizeBtn').click(function () {
        var size = $('#sizeInput').val().trim();
        if (size === '') return;

        var exists = false;
        $('#sizeList input').each(function () {
            if ($(this).val().toLowerCase() === size.toLowerCase()) {
                exists = true;
            }
        });

        if (!exists) {
            var newSize = `
                <div class="input-group mb-2">
                    <input type="text" name="sizes[]" value="${size}" class="form-control" readonly required>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-danger removeSizeBtn">Hapus</button>
                    </div>
                </div>
            `;
            $('#sizeList').append(newSize);
            $('#sizeInput').val('');
            $('#size-error').addClass('d-none');
        } else {
            $('#size-error').removeClass('d-none');
        }
    });

    // Hapus warna
    $(document).on('click', '.removeColorBtn', function () {
        $(this).closest('.input-group').remove();
    });

    // Hapus ukuran
    $(document).on('click', '.removeSizeBtn', function () {
        $(this).closest('.input-group').remove();
    });
});
</script>
@endpush
