@extends('layouts.admin.app')

@section('header')
<header class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Tambah Pesanan Pelanggan</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.cms.customer-orders.index') }}">Pesanan Pelanggan</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </div>
    </div>
</header>
@endsection

@section('content')
<section class="card">
    <article class="card-body">
        <form action="{{ route('admin.cms.customer-orders.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

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
                        @error('customer_id')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <hr>
            <h5>Detail Pengiriman</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="receiver_name">Nama Penerima</label>
                        <input type="text" name="receiver_name" id="receiver_name" class="form-control" value="{{ old('receiver_name') }}" required>
                        @error('receiver_name')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="receiver_phone">Telepon Penerima</label>
                        <input type="tel" name="receiver_phone" id="receiver_phone" class="form-control" value="{{ old('receiver_phone') }}" required>
                        @error('receiver_phone')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="receiver_email">Email Penerima</label>
                        <input type="email" name="receiver_email" id="receiver_email" class="form-control" value="{{ old('receiver_email') }}" required>
                        @error('receiver_email')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="receiver_address">Alamat Pengiriman</label>
                        <textarea name="receiver_address" id="receiver_address" class="form-control" rows="3" required>{{ old('receiver_address') }}</textarea>
                        @error('receiver_address')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <hr>
            <h5>Detail Produk</h5>
            <div id="product-list">
                <div class="row product-item mb-2 align-items-end" data-index="0">
                    <div class="col-md-3">
                        <label class="form-label">Produk</label>
                        <select name="products[0][product_id]" class="form-control product-select" required>
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
                        <input type="number" name="products[0][quantity]" class="form-control quantity-input" min="1" value="1" required>
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

            <div class="form-group mt-3">
                <label>Total Harga</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="text" name="total_price_display" id="total-price-display" class="form-control" value="0" readonly>
                    <input type="hidden" name="total_price" id="total-price-input" value="0">
                </div>
            </div>

            <hr>
            <h5>Detail Pembayaran</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="payment_date">Tanggal Pembayaran</label>
                        <input type="date" name="payment_date" id="payment_date" class="form-control" value="{{ old('payment_date') }}">
                        @error('payment_date')<div class="text-danger small">{{ $message }}</div>@enderror
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
                        @error('payment_method')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="proof_of_payment">Bukti Pembayaran</label>
                        <input type="file" name="proof_of_payment" id="proof_of_payment" class="form-control" accept="image/*">
                        <small class="form-text text-muted">Opsional. Format: JPEG, PNG, JPG, GIF (Maks. 2MB)</small>
                        @error('proof_of_payment')<div class="text-danger small">{{ $message }}</div>@enderror
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
        if (!productId) {
            productItem.find('.color-select, .size-select').empty().append('<option value="">Pilih ...</option>');
            return;
        }

        const url = `{{ url('admin/cms/customer-orders/products') }}/${productId}/variants`;

        $.get(url, function(data) {
            const colorsSelect = productItem.find('.color-select');
            const sizesSelect = productItem.find('.size-select');

            colorsSelect.empty().append('<option value="">Pilih Warna</option>');
            data.colors.forEach(function(color) {
                colorsSelect.append(`<option value="${color}">${color}</option>`);
            });

            sizesSelect.empty().append('<option value="">Pilih Ukuran</option>');
            data.sizes.forEach(function(size) {
                sizesSelect.append(`<option value="${size}">${size}</option>`);
            });
        });
    }

    $(document).ready(function() {
        // Handle customer selection to pre-fill form fields
        $('#customer_id').on('change', function() {
            const selectedCustomer = $(this).find('option:selected');

            // Update form fields. Data is pulled from data-* attributes on the option.
            $('#receiver_name').val(selectedCustomer.data('name') || '');
            $('#receiver_phone').val(selectedCustomer.data('phone') || '');
            $('#receiver_email').val(selectedCustomer.data('email') || '');
            $('#receiver_address').val(selectedCustomer.data('address') || '');
        });

        // Handle product selection to fetch variants
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
                        <select name="products[${newIndex}][product_id]" class="form-control product-select" required>
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
                        <input type="number" name="products[${newIndex}][quantity]" class="form-control quantity-input" min="1" value="1" required>
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
            // Re-attach listeners to the newly added row
            const newProductRow = $(`div[data-index="${newIndex}"]`);
            newProductRow.find('.product-select').on('change', function() {
                updateProductPrice($(this));
                updateTotal();
                fetchAndPopulateVariants($(this).val(), $(this).closest('.product-item'));
            });
            newProductRow.find('.quantity-input').on('input', function() {
                updateTotal();
            });
        });

        $('#product-list').on('click', '.remove-product', function() {
            $(this).closest('.product-item').remove();
            updateTotal();
        });

        // Initialize total on page load for the first product row
        updateTotal();
    });
</script>
@endpush
