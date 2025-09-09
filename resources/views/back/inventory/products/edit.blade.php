@extends('layouts.admin.app')

@section('header')
<header class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Perbaharui Produk</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.inventory.products.index') }}">Produk</a>
                </li>
                <li class="breadcrumb-item active">Perbaharui Produk</li>
            </ol>
        </div>
    </div>
</header>
@endsection

@section('content')
<div class="content-body">
    <form method="POST" action="{{ route('admin.inventory.products.update', $product->id) }}" enctype="multipart/form-data" id="form-edit-product">
        @csrf
        @method('PUT')
        <div class="card shadow-sm">
            <div class="card-body">

                {{-- Nama & SKU --}}
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label>SKU / Barcode <span class="text-danger">*</span></label>
                        <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}" required>
                        @error('sku') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                {{-- Kategori & Jenis Produk --}}
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Kategori <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected':'' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label>Jenis Produk <span class="text-danger">*</span></label>
                        <select name="gender" class="form-control" required>
                            <option value="">-- Pilih Jenis --</option>
                            @foreach($genders as $gender)
                                <option value="{{ $gender }}" {{ old('gender', $product->gender) == $gender ? 'selected' : '' }}>
                                    {{ $gender }}
                                </option>
                            @endforeach
                        </select>
                        @error('gender') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                {{-- Satuan & Harga --}}
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Satuan <span class="text-danger">*</span></label>
                        <input type="text" name="unit" class="form-control" value="{{ old('unit', $product->unit) }}" required>
                        @error('unit') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label>Harga Modal <span class="text-danger">*</span></label>
                        <input type="number" name="cost_price" class="form-control" value="{{ old('cost_price', $product->cost_price) }}" required>
                        @error('cost_price') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Harga Jual <span class="text-danger">*</span></label>
                        <input type="number" name="selling_price" class="form-control" value="{{ old('selling_price', $product->selling_price) }}" required>
                        @error('selling_price') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                {{-- Warna --}}
                {{-- Warna & Ukuran --}}
<div class="form-row">
    <div class="form-group col-md-6">
        <label>Warna <span class="text-danger">*</span></label>
        <div class="input-group" style="max-width: 100%;">
            <input type="text" id="colorInput" class="form-control" placeholder="Masukkan warna">
            <div class="input-group-append">
                <button type="button" id="addColorBtn" class="btn btn-dark btn-sm">Tambah</button>


            </div>
        </div>
        <small id="color-error" class="text-danger d-none">Warna sudah ada!</small>
        <div id="colorList" class="mt-2">
            @foreach($colors as $color)
                <div class="input-group mb-2">
                    <input type="text" name="colors[]" value="{{ $color }}" class="form-control" readonly required>
                    <div class="input-group-append">
                       <button type="button" class="btn btn-danger btn-sm removeColorBtn">Hapus</button>


                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="form-group col-md-6">
        <label>Ukuran <span class="text-danger">*</span></label>
        <div class="input-group" style="max-width: 100%;">
            <input type="text" id="sizeInput" class="form-control" placeholder="Masukkan ukuran">
            <div class="input-group-append">
                <button type="button" id="addSizeBtn" class="btn btn-dark btn-sm">Tambah</button>


            </div>
        </div>
        <small id="size-error" class="text-danger d-none">Ukuran sudah ada!</small>
        <div id="sizeList" class="mt-2">
            @foreach($sizes as $size)
                <div class="input-group mb-2">
                    <input type="text" name="sizes[]" value="{{ $size }}" class="form-control" readonly required>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-danger btn-sm removeSizeBtn">Hapus</button>



                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>




                {{-- Size Chart --}}
                <div class="form-group">
                    <label>Gambar Size Chart (Opsional)</label><br>
                    @if($product->size_chart_image)
    <img src="{{ asset($product->size_chart_image) }}"
         class="img-thumbnail mb-2"
         style="width:100px;height:100px;object-fit:cover;">
@endif

                    <input type="file" name="size_chart_image" class="form-control">
                    @error('size_chart_image') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Foto Produk --}}
                {{-- Foto Produk --}}
<div class="form-group">
    <label>Foto Produk <span class="text-danger">*</span></label>
    <div class="row">
        @foreach($product->images as $image)
        <div class="col-md-3 mb-3">
            <img src="{{ asset($image->image_path) }}"
     class="img-thumbnail"
     style="width:100px;height:100px;object-fit:cover;">

            <button type="button" class="btn btn-danger btn-sm mt-2 removeExistingImage" data-id="{{ $image->id }}">
    Hapus
</button>


        </div>
        @endforeach
    </div>
    <input type="file" name="images[]" class="form-control mt-2" multiple>
    @error('images') <small class="text-danger">{{ $message }}</small> @enderror
</div>


                {{-- Size Details --}}
                <div class="form-group">
                    <label>Detail Ukuran (Opsional)</label>
                    <textarea name="size_details" id="sizeDetailsInput" class="form-control" rows="4">{{ old('size_details', $product->size_details) }}</textarea>
                    @error('size_details') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Deskripsi --}}
                <div class="form-group">
                    <label>Deskripsi <span class="text-danger">*</span></label>
                    <textarea name="description" id="descriptionInput" class="form-control" rows="6" required>{{ old('description', $product->description) }}</textarea>
                </div>

                {{-- Promo Label --}}
                <div class="form-group">
                    <label>Label Promo (Opsional)</label>
                    <select name="promo_label" class="form-control">
                        <option value="">-- Tidak Ada --</option>
                        <option value="Bestseller" {{ old('promo_label', $product->promo_label) == 'Bestseller' ? 'selected':'' }}>Bestseller</option>
                        <option value="Limited Stock" {{ old('promo_label', $product->promo_label) == 'Limited Stock' ? 'selected':'' }}>Limited Stock</option>
                        <option value="New Arrival" {{ old('promo_label', $product->promo_label) == 'New Arrival' ? 'selected':'' }}>New Arrival</option>
                        <option value="Flash Sale" {{ old('promo_label', $product->promo_label) == 'Flash Sale' ? 'selected':'' }}>Flash Sale</option>
                    </select>
                </div>

            </div>
            <div class="card-footer text-right">
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
$(document).ready(function () {
    $('#descriptionInput').summernote({ height: 300 });
    $('#sizeDetailsInput').summernote({ height: 200 });

    // Warna
    $('#addColorBtn').click(function () {
        let val = $('#colorInput').val().trim();
        if (!val) return;
        let exists = false;
        $('#colorList input').each(function () {
            if ($(this).val().toLowerCase() === val.toLowerCase()) exists = true;
        });
        if (!exists) {
            $('#colorList').append(`
                <div class="input-group mb-2">
                    <input type="text" name="colors[]" value="${val}" class="form-control" readonly required>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-danger removeColorBtn">Hapus</button>
                    </div>
                </div>`);
            $('#colorInput').val('');
            $('#color-error').addClass('d-none');
        } else {
            $('#color-error').removeClass('d-none');
        }
    });
    $(document).on('click', '.removeColorBtn', function () {
        $(this).closest('.input-group').remove();
    });

    // Ukuran
    $('#addSizeBtn').click(function () {
        let val = $('#sizeInput').val().trim();
        if (!val) return;
        let exists = false;
        $('#sizeList input').each(function () {
            if ($(this).val().toLowerCase() === val.toLowerCase()) exists = true;
        });
        if (!exists) {
            $('#sizeList').append(`
                <div class="input-group mb-2">
                    <input type="text" name="sizes[]" value="${val}" class="form-control" readonly required>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-danger removeSizeBtn">Hapus</button>
                    </div>
                </div>`);
            $('#sizeInput').val('');
            $('#size-error').addClass('d-none');
        } else {
            $('#size-error').removeClass('d-none');
        }
    });
    $(document).on('click', '.removeSizeBtn', function () {
        $(this).closest('.input-group').remove();
    });

    // Hapus gambar lama
    $(document).on('click', '.removeExistingImage', function () {
        let id = $(this).data('id');
        $(this).closest('.col-md-3').remove();
        $('<input>').attr({
            type: 'hidden',
            name: 'deleted_images[]',
            value: id
        }).appendTo('#form-edit-product');
    });
});
</script>
@endpush
