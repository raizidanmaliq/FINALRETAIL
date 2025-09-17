@extends('layouts.admin.app')

@section('header')
<header class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Tambah Pesanan</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.cms.customer-orders.index') }}">Pesanan</a></li>
                <li class="breadcrumb-item active">Tambah Pesanan</li>
            </ol>
        </div>
    </div>
</header>
@endsection

@section('content')
<section class="card">
    <article class="card-body">
        <form id="form-validation" action="{{ route('admin.cms.customer-orders.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <h5>Informasi Pelanggan</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="customer_id">Pelanggan</label>
                        <select name="customer_id" id="customer_id" class="form-control">
                            <option value="">Pilih Pelanggan (Opsional)</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}
                                    data-name="{{ $customer->name }}"
                                    data-phone="{{ $customer->phone }}"
                                    data-email="{{ $customer->email }}"
                                    data-address="{{ $customer->address }}">
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                </div>
            </div>

            <hr>
            <h5>Detail Pengiriman</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="receiver_name">Nama Penerima <span class="text-danger">*</span></label>
                        <input type="text" name="receiver_name" id="receiver_name" class="form-control" value="{{ old('receiver_name') }}">
                        @error('receiver_name')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="receiver_phone">Telepon Penerima <span class="text-danger">*</span></label>
                        <input type="tel" name="receiver_phone" id="receiver_phone" class="form-control" value="{{ old('receiver_phone') }}">
                        @error('receiver_phone')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="receiver_email">Email Penerima <span class="text-danger">*</span></label>
                        <input type="email" name="receiver_email" id="receiver_email" class="form-control" value="{{ old('receiver_email') }}">
                        @error('receiver_email')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="receiver_address">Alamat Pengiriman <span class="text-danger">*</span></label>
                        <textarea name="receiver_address" id="receiver_address" class="form-control" rows="3">{{ old('receiver_address') }}</textarea>
                        @error('receiver_address')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                </div>
            </div>

            <hr>
            <h5>Detail Produk <span class="text-danger">*</span></h5>
            <div id="product-list">
                <div class="row product-item mb-2 align-items-end" data-index="0">
                    <div class="col-md-3">
                        <label class="form-label">Produk</label>
                        <select name="products[0][product_id]" class="form-control product-select">
                            <option value="">Pilih Produk</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-selling-price="{{ $product->selling_price }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Warna</label>
                        <select name="products[0][color]" class="form-control color-select">
                            <option value="">Pilih Warna</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Ukuran</label>
                        <select name="products[0][size]" class="form-control size-select">
                            <option value="">Pilih Ukuran</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">Jumlah</label>
                        <input type="number" name="products[0][quantity]" class="form-control quantity-input" min="1" value="1">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Harga Unit</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control unit-price-display" value="0" readonly>
                            <input type="hidden" name="products[0][unit_price]" class="unit-price-input" value="0">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger w-100 remove-product"><i class="fa fa-times"></i></button>
                    </div>
                </div>
            </div>
            <button type="button" id="add-product" class="btn btn-info btn-sm mb-3"><i class="fa fa-plus"></i> Tambah Produk</button>
            @error('products')<small class="text-danger d-block mt-2" id="products-error">{{ $message }}</small>@enderror
            <small class="text-danger d-block mt-2 d-none" id="products-error-js">Minimal harus ada satu produk.</small>

            <div class="form-group mt-3">
                <label>Total Harga <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="text" name="total_price_display" id="total-price-display" class="form-control" value="0" readonly>
                    <input type="hidden" name="total_price" id="total-price-input" value="0">
                </div>
            </div>
            @error('total_price')<small class="text-danger d-block mt-2">{{ $message }}</small>@enderror

            <hr>
            <h5>Detail Pembayaran</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="payment_date">Tanggal Pembayaran</label>
                        <input type="date" name="payment_date" id="payment_date" class="form-control" value="{{ old('payment_date') }}">
                        @error('payment_date')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="payment_method">Metode Pembayaran</label>
                        <select name="payment_method" id="payment_method" class="form-control">
                            <option value="">Pilih Metode</option>
                            <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="ewallet" {{ old('payment_method') == 'ewallet' ? 'selected' : '' }}>E-wallet</option>
                            <option value="manual" {{ old('payment_method') == 'manual' ? 'selected' : '' }}>Manual</option>
                        </select>
                        @error('payment_method')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="proof_of_payment">Bukti Pembayaran</label>
                        <input type="file" name="proof_of_payment" id="proof_of_payment" class="form-control" accept="image/*">
                        <small class="form-text text-muted">Opsional. Format: JPEG, PNG, JPG, GIF (Maks. 2MB)</small>
                        @error('proof_of_payment')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-danger float-right"
                    style="background-color:#9B4141; border-color:#9B4141; color:#fff;">
                    <i class="la la-check-square-o"></i> Simpan
                </button>
            </div>
        </form>
    </article>
</section>
@endsection

@push('js')
<script>
    let productIndex = 1;

    function formatCurrency(value) {
        return new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0 }).format(value);
    }

    function updateProductPrice(element) {
        const productItem = element.closest('.product-item');
        const selectedOption = element.find('option:selected');
        const price = selectedOption.data('selling-price') || 0;

        productItem.find('.unit-price-input').val(price);
        productItem.find('.unit-price-display').val(formatCurrency(price));
    }

    function updateTotal() {
        let grandTotal = 0;
        $('.product-item').each(function() {
            const quantity = parseFloat($(this).find('.quantity-input').val()) || 0;
            const unitPrice = parseFloat($(this).find('.unit-price-input').val()) || 0;
            grandTotal += quantity * unitPrice;
        });
        $('#total-price-input').val(grandTotal);
        $('#total-price-display').val(formatCurrency(grandTotal));
    }

    function fetchAndPopulateVariants(productId, productItem) {
        const colorsSelect = productItem.find('.color-select');
        const sizesSelect = productItem.find('.size-select');

        colorsSelect.empty().append('<option value="">Pilih Warna</option>');
        sizesSelect.empty().append('<option value="">Pilih Ukuran</option>');

        if (!productId) {
            return;
        }

        const url = `{{ url('admin/cms/customer-orders/products') }}/${productId}/variants`;

        $.get(url, function(data) {
            data.colors.forEach(function(color) {
                colorsSelect.append(`<option value="${color}">${color}</option>`);
            });

            data.sizes.forEach(function(size) {
                sizesSelect.append(`<option value="${size}">${size}</option>`);
            });
        });
    }

    function addValidationToDynamicFields() {
        $('[name^="products"]').each(function() {
            $(this).rules('remove');
        });

        $('.product-item').each(function() {
            const index = $(this).data('index');
            $(`[name="products[${index}][product_id]"]`).rules('add', {
                required: true,
                messages: { required: "Produk wajib diisi." }
            });
            $(`[name="products[${index}][color]"]`).rules('add', {
                required: true,
                messages: { required: "Warna wajib diisi." }
            });
            $(`[name="products[${index}][size]"]`).rules('add', {
                required: true,
                messages: { required: "Ukuran wajib diisi." }
            });
            $(`[name="products[${index}][quantity]"]`).rules('add', {
                required: true,
                min: 1,
                messages: { required: "Jumlah wajib diisi.", min: "Jumlah harus minimal 1." }
            });
        });
    }

    // Fungsi untuk menambahkan validasi kondisional pada field pembayaran
    function addPaymentValidation() {
        // Cek apakah salah satu dari ketiga field (tanggal, metode, atau bukti) diisi
        const isPaymentFilled = $('#payment_date').val() || $('#payment_method').val() || ($('#proof_of_payment').val() !== '');

        // Hapus aturan lama
        $('#payment_date, #payment_method, #proof_of_payment').rules('remove', 'required');

        if (isPaymentFilled) {
            $('#payment_date').rules('add', {
                required: true,
                messages: { required: "Tanggal pembayaran wajib diisi." }
            });
            $('#payment_method').rules('add', {
                required: true,
                messages: { required: "Metode pembayaran wajib diisi." }
            });
            $('#proof_of_payment').rules('add', {
                required: true,
                messages: { required: "Bukti pembayaran wajib diunggah." }
            });
        }
    }

    $(document).ready(function() {
        $('#form-validation').validate({
            ignore: [],
            rules: {
                receiver_name: { required: true },
                receiver_phone: { required: true, digits: true },
                receiver_email: { required: true, email: true },
                receiver_address: { required: true },
                total_price: { required: true, min: 1 },
            },
            messages: {
                receiver_name: "Nama penerima wajib diisi.",
                receiver_phone: { required: "Telepon penerima wajib diisi.", digits: "Telepon harus berupa angka." },
                receiver_email: { required: "Email penerima wajib diisi.", email: "Format email tidak valid." },
                receiver_address: "Alamat pengiriman wajib diisi.",
                total_price: { required: "Total harga wajib diisi.", min: "Total harga harus lebih dari 0." },
            },
            errorElement: 'small',
            errorClass: 'text-danger',
            errorPlacement: function(error, element) {
                error.insertAfter(element.parent().hasClass('input-group') ? element.parent() : element);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

        $('#form-validation').on('submit', function(e) {
            addValidationToDynamicFields();
            addPaymentValidation();

            if ($('.product-item').length === 0) {
                $('#products-error-js').removeClass('d-none');
                e.preventDefault();
            } else {
                $('#products-error-js').addClass('d-none');
            }

            if (!$(this).valid()) {
                e.preventDefault();
            }
        });

        $('#customer_id').on('change', function() {
            const selectedCustomer = $(this).find('option:selected');
            $('#receiver_name').val(selectedCustomer.data('name') || '');
            $('#receiver_phone').val(selectedCustomer.data('phone') || '');
            $('#receiver_email').val(selectedCustomer.data('email') || '');
            $('#receiver_address').val(selectedCustomer.data('address') || '');
        });

        $('#product-list').on('change', '.product-select', function() {
            const productItem = $(this).closest('.product-item');
            const productId = $(this).val();

            updateProductPrice($(this));
            updateTotal();
            fetchAndPopulateVariants(productId, productItem);
        });

        $('#product-list').on('input', '.quantity-input', function() {
            updateTotal();
        });

        $('#add-product').on('click', function() {
            const newIndex = productIndex++;
            const productHtml = `
                <div class="row product-item mb-2 align-items-end" data-index="${newIndex}">
                    <div class="col-md-3">
                        <label class="form-label">Produk</label>
                        <select name="products[${newIndex}][product_id]" class="form-control product-select">
                            <option value="">Pilih Produk</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-selling-price="{{ $product->selling_price }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Warna</label>
                        <select name="products[${newIndex}][color]" class="form-control color-select">
                            <option value="">Pilih Warna</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Ukuran</label>
                        <select name="products[${newIndex}][size]" class="form-control size-select">
                            <option value="">Pilih Ukuran</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">Jumlah</label>
                        <input type="number" name="products[${newIndex}][quantity]" class="form-control quantity-input" min="1" value="1">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Harga Unit</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control unit-price-display" value="0" readonly>
                            <input type="hidden" name="products[${newIndex}][unit_price]" class="unit-price-input" value="0">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger w-100 remove-product"><i class="fa fa-times"></i></button>
                    </div>
                </div>
            `;
            $('#product-list').append(productHtml);
            addValidationToDynamicFields();
        });

        $('#product-list').on('click', '.remove-product', function() {
            $(this).closest('.product-item').remove();
            updateTotal();
            addValidationToDynamicFields();
        });

        // Add event listeners for payment fields
        $('#payment_date, #payment_method, #proof_of_payment').on('change', function() {
            addPaymentValidation();
        });

        addValidationToDynamicFields();
        updateTotal();
    });
</script>
@endpush
