@extends('layouts.admin.app')

@section('header')
<header class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Tambah Pesanan Supplier</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.purchase-orders.index') }}">Pesanan Supplier</a>
                </li>
                <li class="breadcrumb-item active">Tambah Pesanan Supplier</li>
            </ol>
        </div>
    </div>
</header>
@endsection

@section('content')
<div class="content-body">
    <form id="form-purchase-order" method="POST" action="{{ route('admin.purchase-orders.store') }}" novalidate>
        <div class="card">
            <div class="card-body">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="supplier_id">Nama Supplier</label>
                        <select name="supplier_id" id="supplier_id" class="form-control" required>
                            <option value="">Pilih Supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="order_date">Tanggal Pemesanan</label>
                        <input type="date" name="order_date" id="order_date" class="form-control" value="{{ old('order_date', \Carbon\Carbon::now()->toDateString()) }}" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="arrival_estimate_date">Estimasi Kedatangan Barang</label>
                        <input type="date" name="arrival_estimate_date" id="arrival_estimate_date" class="form-control" value="{{ old('arrival_estimate_date') }}" required>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>

                <hr>

                <h4>Detail Produk</h4>
                <div id="product-list"></div>
                <div id="product-list-error" class="invalid-feedback d-block"></div>

                <button type="button" id="add-product-btn" class="btn btn-dark btn-sm mt-3">
                    <i class="fa fa-plus"></i> Tambah Produk
                </button>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        let productIndex = 0;
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
                $('#arrival_estimate_date').siblings('.invalid-feedback').text('Estimasi Kedatangan Barang harus diisi.').addClass('d-block');
                isValid = false;
            }

            const orderDate = $('#order_date').val();
            const arrivalDate = $('#arrival_estimate_date').val();

            if (arrivalDate && orderDate) {
                if (new Date(arrivalDate) < new Date(orderDate)) {
                    $('#arrival_estimate_date').addClass('is-invalid');
                    $('#arrival_estimate_date').siblings('.invalid-feedback').text('Estimasi Tanggal Kedatangan tidak boleh lebih awal dari Tanggal Pemesanan.').addClass('d-block');
                    isValid = false;
                }
            }

            if ($('.product-row').length === 0) {
                $('#product-list-error').text('Minimal harus ada satu produk.').addClass('d-block');
                isValid = false;
            } else {
                $('.product-row').each(function() {
                    const productSelect = $(this).find('select.product-select');
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
            const productHtml = `
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
                        <input type="number" name="products[${productIndex}][quantity]" class="form-control" min="1" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-group col-md-3">
                        <label>Harga Per Unit</label>
                        <input type="number" name="products[${productIndex}][unit_price]" class="form-control" min="0" step="0.01" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-group col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger remove-product-btn w-100">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            $('#product-list').append(productHtml);
            productIndex++;
        }

        $('#add-product-btn').click(function() {
            addProductRow();
        });

        $(document).on('click', '.remove-product-btn', function() {
            $(this).closest('.product-row').remove();
            if ($('.product-row').length === 0) {
                $('#product-list-error').text('Minimal harus ada satu produk.').addClass('d-block');
            } else {
                $('#product-list-error').text('').removeClass('d-block');
            }
        });

        // Add a row on page load for a better user experience
        addProductRow();

        $('#form-purchase-order').on('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
            }
        });
    });
</script>
@endpush
