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
                    <a href="{{ route('admin.inventory.products.index') }}">Produk</a>
                </li>
                <li class="breadcrumb-item active">Tambah Produk Baru</li>
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
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        {{-- Menambahkan elemen ini untuk pesan error JavaScript --}}
                        <div id="name-error-js" class="invalid-feedback"></div>
                    </div>
                    {{-- SKU --}}
                    <div class="form-group col-md-6">
                        <label for="sku">SKU / Barcode <span class="text-danger">*</span></label>
                        <input type="text" name="sku" id="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku') }}">
                        @error('sku')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="sku-error-js" class="invalid-feedback"></div>
                    </div>
                </div>

                <div class="form-row">
                    {{-- Kategori --}}
                    <div class="form-group col-md-6">
                        <label for="category_select">Kategori <span class="text-danger">*</span></label>
                        <select name="category_id" id="category_select" class="form-control @error('category_id') is-invalid @enderror">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="new" {{ old('category_id') == 'new' ? 'selected' : '' }}>-- Tambah Kategori Baru --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <div id="newCategoryInputGroup" style="display: {{ old('category_id') == 'new' || $errors->has('new_category_name') ? 'block' : 'none' }};" class="mt-2">
                            <label>Nama Kategori Baru</label>
                            <input type="text" name="new_category_name" id="newCategoryInput" class="form-control @error('new_category_name') is-invalid @enderror" placeholder="Nama Kategori Baru" value="{{ old('new_category_name') }}">
                            @error('new_category_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div id="category-error-js" class="invalid-feedback"></div>
                    </div>
                    {{-- Jenis Produk --}}
                    <div class="form-group col-md-6">
                        <label for="gender">Jenis Produk <span class="text-danger">*</span></label>
                        <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror">
                            <option value="">-- Pilih Jenis --</option>
                            @foreach($genders as $gender)
                                <option value="{{ $gender }}" {{ old('gender') == $gender ? 'selected' : '' }}>
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

                <div class="form-row">
                    {{-- Satuan --}}
                    <div class="form-group col-md-6">
                        <label for="unit">Satuan <span class="text-danger">*</span></label>
                        <input type="text" name="unit" id="unit" class="form-control @error('unit') is-invalid @enderror" value="{{ old('unit') }}">
                        @error('unit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="unit-error-js" class="invalid-feedback"></div>
                    </div>
                    {{-- Harga Modal --}}
                    <div class="form-group col-md-6">
                        <label for="cost_price">Harga Modal <span class="text-danger">*</span></label>
                        <input type="number" name="cost_price" id="cost_price" class="form-control @error('cost_price') is-invalid @enderror" value="{{ old('cost_price') }}">
                        @error('cost_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="cost_price-error-js" class="invalid-feedback"></div>
                    </div>
                </div>

                <div class="form-row">
                    {{-- Harga Jual --}}
                    <div class="form-group col-md-6">
                        <label for="selling_price">Harga Jual <span class="text-danger">*</span></label>
                        <input type="number" name="selling_price" id="selling_price" class="form-control @error('selling_price') is-invalid @enderror" value="{{ old('selling_price') }}">
                        @error('selling_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="selling_price-error-js" class="invalid-feedback"></div>
                    </div>
                    {{-- Stok Awal --}}
                    <div class="form-group col-md-6">
                        <label for="inventory">Stok Awal <span class="text-danger">*</span></label>
                        <input type="number" name="inventory" id="inventory" class="form-control @error('inventory') is-invalid @enderror" value="{{ old('inventory') }}">
                        @error('inventory')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="inventory-error-js" class="invalid-feedback"></div>
                    </div>
                </div>

                <div class="form-row">
                    {{-- Warna --}}
                    <div class="form-group col-md-6">
                        <label for="colorInput">Warna (Maks. 5 warna)<span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" id="colorInput" class="form-control" placeholder="Masukkan warna">
                            <div class="input-group-append">
                                <button type="button" id="addColorBtn" class="btn btn-dark btn-sm">Tambah</button>
                            </div>
                        </div>
                        <div id="colorList" class="mt-2"></div>
                        <div id="colors-error-js" class="invalid-feedback d-block"></div>
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
                        <div id="sizeList" class="mt-2"></div>
                        <div id="sizes-error-js" class="invalid-feedback d-block"></div>
                    </div>
                </div>

                <div class="form-row">
                    {{-- Media Produk (WAJIB) --}}
                    <div class="form-group col-md-6">
                        <label for="media">Foto & Video Produk (Maks. 6 file) <span class="text-danger">*</span></label>
                        <input type="file" name="media[]" id="mediaInput" class="form-control @error('media') is-invalid @enderror @error('media.*') is-invalid @enderror" multiple>
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
                    <div class="form-group col-md-6">
                        <label for="size_chart_image">Gambar Size Chart (Opsional) </label>
                        <input type="file" name="size_chart_image" id="sizeChartInput" class="form-control @error('size_chart_image') is-invalid @enderror">
                        <div id="preview-size-chart" class="mt-2"></div>
                        @error('size_chart_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Detail Ukuran (Opsional) --}}
                <div class="form-group">
                    <label for="size_details">Detail Ukuran (Opsional)</label>
                    <textarea name="size_details" id="sizeDetailsInput" class="form-control @error('size_details') is-invalid @enderror" rows="5">{{ old('size_details') }}</textarea>
                    @error('size_details')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Deskripsi (WAJIB) --}}
                <div class="form-group">
                    <label for="description">Deskripsi <span class="text-danger">*</span></label>
                    <textarea name="description" id="descriptionInput" class="form-control @error('description') is-invalid @enderror" rows="6">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div id="description-error-js" class="invalid-feedback"></div>
                </div>

                {{-- Label Promo (Opsional) --}}
                <div class="form-group">
                    <label for="promo_label">Label Promo (Opsional)</label>
                    <select name="promo_label" class="form-control @error('promo_label') is-invalid @enderror">
                        <option value="">-- Tidak Ada --</option>
                        <option value="Bestseller" {{ old('promo_label') == 'Bestseller' ? 'selected' : '' }}>Bestseller</option>
                        <option value="Limited Stock" {{ old('promo_label') == 'Limited Stock' ? 'selected' : '' }}>Limited Stock</option>
                        <option value="New Arrival" {{ old('promo_label') == 'New Arrival' ? 'selected' : '' }}>New Arrival</option>
                        <option value="Flash Sale" {{ old('promo_label') == 'Flash Sale' ? 'selected' : '' }}>Flash Sale</option>
                    </select>
                    @error('promo_label')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="card-footer text-right">
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
    $(document).ready(function () {
        // Summernote
        $('#descriptionInput').summernote({ height: 300 });
        $('#sizeDetailsInput').summernote({ height: 200 });

        // Tampilkan/sembunyikan input kategori baru
        $('#category_select').on('change', function() {
            if ($(this).val() === 'new') {
                $('#newCategoryInputGroup').show();
            } else {
                $('#newCategoryInputGroup').hide();
                $('input[name="new_category_name"]').val('');
            }
            $('#category_select').removeClass('is-invalid');
            $('#category-error-js').text('').hide();
        });

        // Hapus class error saat input diisi
        $('input, select, textarea').on('input change', function() {
            $(this).removeClass('is-invalid');
            $(this).siblings('.invalid-feedback').text('').hide();
            if ($(this).attr('id') === 'colorInput' || $(this).attr('id') === 'sizeInput') {
                $('#colors-error-js').text('').hide();
                $('#sizes-error-js').text('').hide();
            }
        });

        // Pratinjau media
        $('#mediaInput').on('change', function () {
            $('#preview-media').empty();
            let videoCount = 0;
            const files = Array.from(this.files);

            files.forEach(file => {
                if (file.type.startsWith('video')) {
                    videoCount++;
                }
            });

            if (videoCount > 1) {
                alert('Hanya bisa mengunggah maksimal satu video.');
                this.value = '';
                return;
            }

            if (files.length > 6) {
                $('#mediaInput').addClass('is-invalid');
                $('#media-error-js').text('Media produk maksimal 6 file.').show();
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
                        $('#preview-media').append(`
                            <video controls class="img-thumbnail m-1" style="width:100px;height:100px;object-fit:cover;">
                                <source src="${previewUrl}" type="${file.type}">
                            </video>
                        `);
                    } else {
                        $('#preview-media').append(`<img src="${previewUrl}" class="img-thumbnail m-1" style="width:100px;height:100px;object-fit:cover;">`);
                    }
                };
                reader.readAsDataURL(file);
            });
        });

        // Pratinjau size chart
        $('#sizeChartInput').on('change', function () {
            $('#preview-size-chart').empty();
            let file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = (e) => {
                    $('#preview-size-chart').append(`
                        <img src="${e.target.result}"
                             class="img-thumbnail m-1"
                             style="width:100px;height:100px;object-fit:cover;">
                    `);
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
                var newColor = `
                    <div class="input-group mb-2">
                        <input type="text" name="colors[]" value="${color}" class="form-control" readonly required>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-danger btn-sm removeColorBtn">Hapus</button>
                        </div>
                    </div>
                `;
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
                var newSize = `
                    <div class="input-group mb-2">
                        <input type="text" name="sizes[]" value="${size}" class="form-control" readonly required>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-danger btn-sm removeSizeBtn">Hapus</button>
                        </div>
                    </div>
                `;
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

        // Validasi terpusat saat submit
        $('#form-product').on('submit', function (e) {
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
                {id: 'inventory', label: 'Stok Awal'},
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
            const selectedCategory = $('#category_select').val();
            const newCategoryName = $('#newCategoryInput').val().trim();
            if (!selectedCategory) {
                isValid = false;
                $('#category_select').addClass('is-invalid');
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

            // 6. Validasi Media
            const totalMedia = $('#mediaInput').prop('files').length;
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
