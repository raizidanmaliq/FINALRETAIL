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
                        <label for="name">Nama Produk <span class="text-danger">*</span></label>
                        {{-- Atribut required dihapus --}}
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="name-error-js" class="invalid-feedback"></div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="sku">SKU / Barcode <span class="text-danger">*</span></label>
                        {{-- Atribut required dihapus --}}
                        <input type="text" name="sku" id="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku', $product->sku) }}">
                        @error('sku')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="sku-error-js" class="invalid-feedback"></div>
                    </div>
                </div>

                {{-- Kategori & Jenis Produk --}}
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="categorySelect">Kategori <span class="text-danger">*</span></label>
                        {{-- Atribut required dihapus --}}
                        <select name="category_id" id="categorySelect" class="form-control @error('category_id') is-invalid @enderror">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="new">-- Tambah Kategori Baru --</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected':'' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="newCategoryInputGroup" class="mt-2" style="display: {{ old('category_id') == 'new' || ($errors->has('new_category_name') && empty(old('category_id'))) ? 'block' : 'none' }};">
                            <label>Nama Kategori Baru</label>
                            {{-- Atribut required dihapus --}}
                            <input type="text" name="new_category_name" id="newCategoryInput" class="form-control @error('new_category_name') is-invalid @enderror" placeholder="Masukkan nama kategori" value="{{ old('new_category_name') }}">
                            @error('new_category_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div id="category-error-js" class="invalid-feedback"></div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="gender">Jenis Produk <span class="text-danger">*</span></label>
                        {{-- Atribut required dihapus --}}
                        <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror">
                            <option value="">-- Pilih Jenis --</option>
                            @foreach($genders as $gender)
                            <option value="{{ $gender }}" {{ old('gender', $product->gender) == $gender ? 'selected' : '' }}>
                                {{ $gender }}
                            </option>
                            @endforeach
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="gender-error-js" class="invalid-feedback"></div>
                    </div>
                </div>

                {{-- Satuan & Harga --}}
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="unit">Satuan <span class="text-danger">*</span></label>
                        {{-- Atribut required dihapus --}}
                        <input type="text" name="unit" id="unit" class="form-control @error('unit') is-invalid @enderror" value="{{ old('unit', $product->unit) }}">
                        @error('unit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="unit-error-js" class="invalid-feedback"></div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="cost_price">Harga Modal <span class="text-danger">*</span></label>
                        {{-- Atribut required dihapus --}}
                        <input type="number" name="cost_price" id="cost_price" class="form-control @error('cost_price') is-invalid @enderror" value="{{ old('cost_price', $product->cost_price) }}">
                        @error('cost_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="cost_price-error-js" class="invalid-feedback"></div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="selling_price">Harga Jual <span class="text-danger">*</span></label>
                        {{-- Atribut required dihapus --}}
                        <input type="number" name="selling_price" id="selling_price" class="form-control @error('selling_price') is-invalid @enderror" value="{{ old('selling_price', $product->selling_price) }}">
                        @error('selling_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="selling_price-error-js" class="invalid-feedback"></div>
                    </div>
                </div>

                {{-- Warna & Ukuran --}}
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="colorInput">Warna (Maks. 5 warna)<span class="text-danger">*</span></label>
                        <div class="input-group" style="max-width: 100%;">
                            <input type="text" id="colorInput" class="form-control" placeholder="Masukkan warna">
                            <div class="input-group-append">
                                <button type="button" id="addColorBtn" class="btn btn-dark btn-sm">Tambah</button>
                            </div>
                        </div>
                        <div id="colorList" class="mt-2">
                            @foreach($product->variants->pluck('color')->unique() as $color)
                                <div class="input-group mb-2">
                                    <input type="text" name="colors[]" value="{{ $color }}" class="form-control" readonly>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-danger btn-sm removeColorBtn">Hapus</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div id="colors-error-js" class="invalid-feedback d-block"></div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="sizeInput">Ukuran <span class="text-danger">*</span></label>
                        <div class="input-group" style="max-width: 100%;">
                            <input type="text" id="sizeInput" class="form-control" placeholder="Masukkan ukuran">
                            <div class="input-group-append">
                                <button type="button" id="addSizeBtn" class="btn btn-dark btn-sm">Tambah</button>
                            </div>
                        </div>
                        <div id="sizeList" class="mt-2">
                            @foreach($product->variants->pluck('size')->unique() as $size)
                                <div class="input-group mb-2">
                                    <input type="text" name="sizes[]" value="{{ $size }}" class="form-control" readonly>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-danger btn-sm removeSizeBtn">Hapus</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div id="sizes-error-js" class="invalid-feedback d-block"></div>
                    </div>
                </div>

                {{-- Media Produk (WAJIB) --}}
                <div class="form-group">
                    <label for="mediaInput">Foto & Video Produk (Maks. 6 file) <span class="text-danger">*</span></label>
                    <div id="existing-media" class="mt-2 d-flex flex-wrap">
                        @foreach($product->images as $media)
                            <div class="media-container position-relative m-1" style="width:100px;height:100px;">
                                @if($media->is_video)
                                    <video controls class="img-thumbnail" style="width:100%;height:100%;object-fit:cover;">
                                        <source src="{{ asset($media->image_path) }}" type="video/mp4">
                                    </video>
                                @else
                                    <img src="{{ asset($media->image_path) }}" class="img-thumbnail" style="width:100%;height:100%;object-fit:cover;">
                                @endif
                                <button type="button" class="btn btn-danger btn-sm removeExistingMedia position-absolute" style="top:5px;right:5px;z-index:1;" data-id="{{ $media->id }}">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                    {{-- Atribut required dihapus --}}
                    <input type="file" name="media[]" id="mediaInput" class="form-control mt-2 @error('media') is-invalid @enderror @error('media.*') is-invalid @enderror" multiple>
                    <div id="preview-media" class="mt-2 d-flex flex-wrap"></div>
                    <div id="media-error-js" class="invalid-feedback"></div>
                    @error('media')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    @error('media.*')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Size Chart (Opsional) --}}
                <div class="form-group">
                    <label for="sizeChartInput">Gambar Size Chart (Opsional)</label><br>
                    <div id="preview-size-chart" class="mt-2">
                         @if($product->size_chart_image)
                             <img src="{{ asset($product->size_chart_image) }}" class="img-thumbnail mb-2" style="width:100px;height:100px;object-fit:cover;">
                         @endif
                    </div>
                    <input type="file" name="size_chart_image" id="sizeChartInput" class="form-control @error('size_chart_image') is-invalid @enderror">
                    @error('size_chart_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Size Details --}}
                <div class="form-group">
                    <label for="sizeDetailsInput">Detail Ukuran (Opsional)</label>
                    <textarea name="size_details" id="sizeDetailsInput" class="form-control @error('size_details') is-invalid @enderror" rows="4">{{ old('size_details', $product->size_details) }}</textarea>
                    @error('size_details')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div class="form-group">
                    <label for="descriptionInput">Deskripsi <span class="text-danger">*</span></label>
                    {{-- Atribut required dihapus --}}
                    <textarea name="description" id="descriptionInput" class="form-control @error('description') is-invalid @enderror" rows="6">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div id="description-error-js" class="invalid-feedback"></div>
                </div>

                {{-- Promo Label --}}
                <div class="form-group">
                    <label for="promo_label">Label Promo (Opsional)</label>
                    <select name="promo_label" id="promo_label" class="form-control @error('promo_label') is-invalid @enderror">
                        <option value="">-- Tidak Ada --</option>
                        <option value="Bestseller" {{ old('promo_label', $product->promo_label) == 'Bestseller' ? 'selected':'' }}>Bestseller</option>
                        <option value="Limited Stock" {{ old('promo_label', $product->promo_label) == 'Limited Stock' ? 'selected':'' }}>Limited Stock</option>
                        <option value="New Arrival" {{ old('promo_label', $product->promo_label) == 'New Arrival' ? 'selected':'' }}>New Arrival</option>
                        <option value="Flash Sale" {{ old('promo_label', $product->promo_label) == 'Flash Sale' ? 'selected':'' }}>Flash Sale</option>
                    </select>
                    @error('promo_label')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
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

        // Tampilkan/sembunyikan input kategori baru
        $('#categorySelect').on('change', function() {
            if ($(this).val() === 'new') {
                $('#newCategoryInputGroup').show();
            } else {
                $('#newCategoryInputGroup').hide();
                $('#newCategoryInput').val('');
            }
            $('#categorySelect').removeClass('is-invalid');
            $('#category-error-js').text('').hide();
        });

        // Hapus class error saat input diisi
        $('input, select, textarea').on('input change', function() {
            $(this).removeClass('is-invalid');
            $(this).siblings('.invalid-feedback').text('').hide();
            const id = $(this).attr('id');
            if (id) {
                $(`#${id}-error-js`).text('').hide();
            }
        });

        // Pratinjau media baru (gambar & video)
        $('#mediaInput').on('change', function () {
            $('#preview-media').empty();
            let videoCount = 0;
            const existingVideos = $('#existing-media video').length;
            const files = Array.from(this.files);

            files.forEach(file => {
                if (file.type.startsWith('video')) {
                    videoCount++;
                }
            });

            if (videoCount + existingVideos > 1) {
                alert('Hanya bisa mengunggah maksimal satu video.');
                this.value = '';
                return;
            }

            if (files.length + $('#existing-media .media-container').length > 6) {
                $('#mediaInput').addClass('is-invalid');
                $('#media-error-js').text('Media produk maksimal 6 file (termasuk yang sudah ada).').show();
                this.value = '';
                return;
            } else {
                $('#mediaInput').removeClass('is-invalid');
                $('#media-error-js').text('').hide();
            }

            files.forEach(file => {
                let reader = new FileReader();
                reader.onload = (e) => {
                    const previewUrl = e.target.result;
                    const isVideo = file.type.startsWith('video');
                    if (isVideo) {
                        $('#preview-media').append(`<video controls class="img-thumbnail m-1" style="width:100px;height:100px;object-fit:cover;"><source src="${previewUrl}" type="${file.type}"></video>`);
                    } else {
                        $('#preview-media').append(`<img src="${previewUrl}" class="img-thumbnail m-1" style="width:100px;height:100px;object-fit:cover;">`);
                    }
                };
                reader.readAsDataURL(file);
            });
        });

        // Pratinjau size chart
        $('#sizeChartInput').on('change', function () {
            $('#preview-size-chart img').remove(); // Hapus gambar lama
            let file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = (e) => {
                    $('#preview-size-chart').append(`<img src="${e.target.result}" class="img-thumbnail m-1" style="width:100px;height:100px;object-fit:cover;">`);
                }
                reader.readAsDataURL(file);
            }
        });

        // Tambah warna
        $('#addColorBtn').click(function () {
            var color = $('#colorInput').val().trim();
            if (color === '') return;

            if ($('#colorList input').length >= 5) {
                alert('Anda hanya bisa menambahkan maksimal 5 warna.');
                return;
            }

            var exists = false;
            $('#colorList input').each(function () {
                if ($(this).val().toLowerCase() === color.toLowerCase()) {
                    exists = true;
                }
            });

            if (!exists) {
                var newColor = `<div class="input-group mb-2"><input type="text" name="colors[]" value="${color}" class="form-control" readonly><div class="input-group-append"><button type="button" class="btn btn-danger btn-sm removeColorBtn">Hapus</button></div></div>`;
                $('#colorList').append(newColor);
                $('#colorInput').val('');
                $('#colors-error-js').text('').hide();
            } else {
                $('#colors-error-js').text('Warna ini sudah ada.').show();
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
                var newSize = `<div class="input-group mb-2"><input type="text" name="sizes[]" value="${size}" class="form-control" readonly><div class="input-group-append"><button type="button" class="btn btn-danger btn-sm removeSizeBtn">Hapus</button></div></div>`;
                $('#sizeList').append(newSize);
                $('#sizeInput').val('');
                $('#sizes-error-js').text('').hide();
            } else {
                $('#sizes-error-js').text('Ukuran ini sudah ada.').show();
            }
        });

        // Hapus warna
        $(document).on('click', '.removeColorBtn', function () {
            $(this).closest('.input-group').remove();
            if ($('#colorList').children().length === 0) {
                $('#colors-error-js').text('Pilih setidaknya satu warna.').show();
            }
        });

        // Hapus ukuran
        $(document).on('click', '.removeSizeBtn', function () {
            $(this).closest('.input-group').remove();
            if ($('#sizeList').children().length === 0) {
                $('#sizes-error-js').text('Pilih setidaknya satu ukuran.').show();
            }
        });

        // Hapus media lama
        $(document).on('click', '.removeExistingMedia', function () {
            let id = $(this).data('id');
            $(this).closest('.media-container').remove();
            $('<input>').attr({
                type: 'hidden',
                name: 'deleted_media[]',
                value: id
            }).appendTo('#form-edit-product');
            // Cek jika media menjadi kosong setelah dihapus
            if ($('#mediaInput').prop('files').length + $('#existing-media .media-container').length === 0) {
                $('#mediaInput').addClass('is-invalid');
                $('#media-error-js').text('Unggah setidaknya satu media produk.').show();
            }
        });

        // Validasi terpusat saat submit
        $('#form-edit-product').on('submit', function (e) {
            let isValid = true;

            // Hapus semua tampilan error sebelumnya
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('').hide();

            // 1. Validasi input standar
            const requiredFields = [
                {id: 'name', label: 'Nama Produk'},
                {id: 'sku', label: 'SKU / Barcode'},
                {id: 'unit', label: 'Satuan'},
                {id: 'cost_price', label: 'Harga Modal'},
                {id: 'selling_price', label: 'Harga Jual'},
                {id: 'gender', label: 'Jenis Produk'},
            ];
            requiredFields.forEach(field => {
                const input = $(`#${field.id}`);
                if (input.val() === '' || input.val() === null) {
                    isValid = false;
                    input.addClass('is-invalid');
                    $(`#${field.id}-error-js`).text(`${field.label} tidak boleh kosong.`).show();
                }
            });

            // 2. Validasi Kategori & Nama Kategori Baru
            const selectedCategory = $('#categorySelect').val();
            const newCategoryName = $('#newCategoryInput').val().trim();
            if (!selectedCategory) {
                isValid = false;
                $('#categorySelect').addClass('is-invalid');
                $('#category-error-js').text('Pilih kategori atau buat kategori baru.').show();
            } else if (selectedCategory === 'new' && !newCategoryName) {
                isValid = false;
                $('#newCategoryInput').addClass('is-invalid');
                $('#category-error-js').text('Nama kategori baru tidak boleh kosong.').show();
            }

            // 3. Validasi Warna
            if ($('#colorList').children().length === 0) {
                isValid = false;
                $('#colorInput').addClass('is-invalid');
                $('#colors-error-js').text('Pilih setidaknya satu warna.').show();
            }

            // 4. Validasi Ukuran
            if ($('#sizeList').children().length === 0) {
                isValid = false;
                $('#sizeInput').addClass('is-invalid');
                $('#sizes-error-js').text('Pilih setidaknya satu ukuran.').show();
            }

            // 5. Validasi Deskripsi (Summernote)
            const descriptionContent = $('#descriptionInput').summernote('isEmpty');
            if (descriptionContent) {
                isValid = false;
                $('#descriptionInput').addClass('is-invalid');
                $('#description-error-js').text('Deskripsi tidak boleh kosong.').show();
            }

            // 6. Validasi media
            const totalMedia = $('#mediaInput').prop('files').length + $('#existing-media .media-container').length;
            if (totalMedia === 0) {
                isValid = false;
                $('#mediaInput').addClass('is-invalid');
                $('#media-error-js').text('Unggah setidaknya satu media produk.').show();
            }

            if (!isValid) {
                e.preventDefault();
                const firstInvalid = $('.is-invalid').first();
                if (firstInvalid.length) {
                    $([document.documentElement, document.body]).animate({
                        scrollTop: firstInvalid.offset().top - 100
                    }, 500);
                }
            }
        });
    });
</script>
@endpush
