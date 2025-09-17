@extends('layouts.admin.app')

@section('header')
<header class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Perbaharui Pesanan Supplier</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.purchase-orders.index') }}">Pesanan Supplier</a></li>
                <li class="breadcrumb-item active">Perbaharui Pesanan Supplier</li>
            </ol>
        </div>
    </div>
</header>
@endsection

@section('content')
<section class="card">
    <article class="card-body">
        <form id="form-purchase-order" action="{{ route('admin.purchase-orders.update', $purchaseOrder) }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="supplier_id">Nama Supplier</label>
                    <select name="supplier_id" id="supplier_id" class="form-control" required>
                        <option value="">Pilih Supplier</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id', $purchaseOrder->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group col-md-6">
                    <label for="order_date">Tanggal Pemesanan</label>
                    <input type="date" name="order_date" id="order_date" class="form-control" value="{{ old('order_date', $purchaseOrder->order_date) }}" required>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group col-md-6">
                    <label for="arrival_estimate_date">Estimasi Tanggal Tiba</label>
                    <input type="date" name="arrival_estimate_date" id="arrival_estimate_date" class="form-control" value="{{ old('arrival_estimate_date', $purchaseOrder->arrival_estimate_date) }}" required>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group col-md-6">
                    <label for="status">Status Pemesanan</label>
                    <select name="status" id="status" class="form-control">
                        <option value="pending" {{ old('status', $purchaseOrder->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="on_delivery" {{ old('status', $purchaseOrder->status) == 'on_delivery' ? 'selected' : '' }}>On Delivery</option>
                        <option value="completed" {{ old('status', $purchaseOrder->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            <hr>

            <h5>Detail Produk</h5>
            <div id="product-list">
                @foreach ($purchaseOrder->details as $index => $detail)
                <div class="form-row product-row mt-3">
                    <div class="form-group col-md-5">
                        <label class="d-md-none">Produk</label>
                        <select name="products[{{ $index }}][product_id]" class="form-control product-select" required>
                            <option value="">Pilih Produk</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" {{ $detail->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-group col-md-3">
                        <label class="d-md-none">Jumlah</label>
                        <input type="number" name="products[{{ $index }}][quantity]" class="form-control" placeholder="Jumlah" value="{{ old('products.' . $index . '.quantity', $detail->quantity) }}" required min="1">
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-group col-md-3">
                        <label class="d-md-none">Harga Unit</label>
                        <input type="number" name="products[{{ $index }}][unit_price]" class="form-control" placeholder="Harga Unit" value="{{ old('products.' . $index . '.unit_price', $detail->unit_price) }}" step="0.01" required min="0.01">
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-group col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger remove-product w-100">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="invalid-feedback d-block" id="product-list-error"></div>

            <button type="button" id="add-product-btn" class="btn btn-dark btn-sm mt-3">
                <i class="fa fa-plus"></i> Tambah Produk
            </button>

            <div class="card-footer text-right">
                <button type="submit" class="btn btn-danger"
                    style="background-color:#9B4141; border-color:#9B4141; color:#fff;">
                    <i class="la la-check-square-o"></i> Simpan
                </button>
            </div>
        </form>
    </article>
</section>
@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        let productIndex = {{ $purchaseOrder->details->count() }};
        const products = @json($products);

        function validateForm() {
            let isValid = true;
            $('.invalid-feedback').text('').removeClass('d-block');
            $('input, select').removeClass('is-invalid');

            if (!$('#supplier_id').val()) {
                $('#supplier_id').addClass('is-invalid');
                $('#supplier_id').siblings('.invalid-feedback').text('Nama Supplier harus diisi.').addClass('d-block');
                isValid = false;
            }

            if (!$('#order_date').val()) {
                $('#order_date').addClass('is-invalid');
                $('#order_date').siblings('.invalid-feedback').text('Tanggal Pemesanan harus diisi.').addClass('d-block');
                isValid = false;
            }

            if (!$('#arrival_estimate_date').val()) {
                $('#arrival_estimate_date').addClass('is-invalid');
                $('#arrival_estimate_date').siblings('.invalid-feedback').text('Estimasi Tanggal Tiba harus diisi.').addClass('d-block');
                isValid = false;
            }

            const orderDate = $('#order_date').val();
            const arrivalDate = $('#arrival_estimate_date').val();

            if (arrivalDate && orderDate) {
                if (new Date(arrivalDate) < new Date(orderDate)) {
                    $('#arrival_estimate_date').addClass('is-invalid');
                    $('#arrival_estimate_date').siblings('.invalid-feedback').text('Estimasi Tanggal Tiba tidak boleh lebih awal dari Tanggal Pemesanan.').addClass('d-block');
                    isValid = false;
                }
            }

            if ($('.product-row').length === 0) {
                $('#product-list-error').text('Minimal harus ada satu produk.').addClass('d-block');
                isValid = false;
            } else {
                $('.product-row').each(function() {
                    const productSelect = $(this).find('select[name*="[product_id]"]');
                    const quantityInput = $(this).find('input[name*="[quantity]"]');
                    const priceInput = $(this).find('input[name*="[unit_price]"]');

                    if (!productSelect.val()) {
                        productSelect.addClass('is-invalid');
                        productSelect.siblings('.invalid-feedback').text('Produk harus dipilih.').addClass('d-block');
                        isValid = false;
                    }
                    if (quantityInput.val() === '' || parseInt(quantityInput.val()) < 1) {
                        quantityInput.addClass('is-invalid');
                        quantityInput.siblings('.invalid-feedback').text('Jumlah harus diisi dan minimal 1.').addClass('d-block');
                        isValid = false;
                    }
                    if (priceInput.val() === '' || parseFloat(priceInput.val()) < 0.01) {
                        priceInput.addClass('is-invalid');
                        priceInput.siblings('.invalid-feedback').text('Harga per unit harus diisi dan minimal 0.01.').addClass('d-block');
                        isValid = false;
                    }
                });
            }

            return isValid;
        }

        function addProductRow() {
            const newRow = `
                <div class="form-row product-row mt-3">
                    <div class="form-group col-md-5">
                        <label>Produk</label>
                        <select name="products[${productIndex}][product_id]" class="form-control product-select" required>
                            <option value="">Pilih Produk</option>
                            ${products.map(p => `<option value="${p.id}">${p.name}</option>`).join('')}
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-group col-md-3">
                        <label>Jumlah</label>
                        <input type="number" name="products[${productIndex}][quantity]" class="form-control" placeholder="Jumlah" required min="1">
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-group col-md-3">
                        <label>Harga Unit</label>
                        <input type="number" name="products[${productIndex}][unit_price]" class="form-control" placeholder="Harga Unit" step="0.01" required min="0.01">
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-group col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger remove-product w-100">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            $('#product-list').append(newRow);
            productIndex++;
        }

        // Add new product row functionality
        $('#add-product-btn').click(addProductRow);

        // Remove product row functionality
        $(document).on('click', '.remove-product', function() {
            $(this).closest('.product-row').remove();
            if ($('.product-row').length === 0) {
                $('#product-list-error').text('Minimal harus ada satu produk.').addClass('d-block');
            } else {
                $('#product-list-error').text('').removeClass('d-block');
            }
        });

        // Handle form submission
        $('#form-purchase-order').on('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
            }
        });
    });
</script>
@endpush
