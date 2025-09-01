@extends('layouts.admin.app')

@section('header')
<header class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Edit Pesanan Pelanggan</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.cms.customer-orders.index') }}">Pesanan Pelanggan</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
    </div>
</header>
@endsection

@section('content')
<section class="card">
    <article class="card-body">
        <form action="{{ route('admin.cms.customer-orders.update', $order->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Kode Pesanan --}}
            <div class="form-group">
                <label for="order_code">Kode Pesanan</label>
                <input type="text" class="form-control" value="{{ $order->order_code }}" readonly>
                <input type="hidden" name="order_code" value="{{ $order->order_code }}">
            </div>

            {{-- Status Pesanan --}}
            <div class="form-group">
                <label for="status">Status Pesanan</label>
                <select name="order_status" id="order_status" class="form-control" required>
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Diproses</option>
                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Dikirim</option>
                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            {{-- Pelanggan --}}
            <div class="form-group">
                <label for="customer_id">Pelanggan</label>
                <select name="customer_id" id="customer_id" class="form-control" required>
                    <option value="">Pilih Pelanggan</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ $order->customer_id == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Alamat --}}
            <div class="form-group">
                <label for="shipping_address">Alamat Pengiriman</label>
                <textarea name="shipping_address" id="shipping_address" class="form-control">{{ $order->shipping_address }}</textarea>
            </div>

            <hr>
            <h5>Detail Produk</h5>
            <div id="product-list">
                @foreach($order->items as $index => $item)
                <div class="row product-item mb-2">
                    <div class="col-md-5">
                        <label>Produk</label>
                        <select name="products[{{ $index }}][product_id]" class="form-control product-select" required>
                            <option value="">Pilih Produk</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}"
                                    data-selling-price="{{ $product->selling_price }}"
                                    {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Jumlah</label>
                        <input type="number" name="products[{{ $index }}][quantity]" class="form-control quantity-input"
                               min="1" value="{{ $item->quantity }}" required>
                    </div>
                    <div class="col-md-3">
                        <label>Harga Unit</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="text" class="form-control unit-price-display"
                                   value="{{ number_format($item->price, 0, ',', '.') }}" readonly>
                            <input type="hidden" name="products[{{ $index }}][unit_price]" class="unit-price-input"
                                   value="{{ $item->price }}">
                        </div>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger remove-product"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                @endforeach
            </div>
            <button type="button" id="add-product" class="btn btn-info btn-sm mb-3"><i class="fa fa-plus"></i> Tambah Produk</button>

            <div class="form-group mt-3">
                <label>Total Harga</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                    </div>
                    <input type="text" name="total_price" id="total-price" class="form-control" value="0" readonly>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update Pesanan</button>
        </form>
    </article>
</section>
@endsection

@push('js')
<script>
    let productIndex = {{ $order->items->count() }};

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
        $('#total-price').val(formatCurrency(grandTotal));
    }

    $(document).ready(function() {
        $('#product-list .product-select').each(function() {
            const productItem = $(this).closest('.product-item');
            const unitInput = productItem.find('.unit-price-input');
            if (!unitInput.val() || unitInput.val() == 0) {
                updateProductPrice($(this));
            }
        });

        updateTotal();

        $('#product-list').on('change', '.product-select', function() {
            updateProductPrice($(this));
            updateTotal();
        });

        $('#add-product').on('click', function() {
            const productHtml = `
                <div class="row product-item mb-2">
                    <div class="col-md-5">
                        <label>Produk</label>
                        <select name="products[${productIndex}][product_id]" class="form-control product-select" required>
                            <option value="">Pilih Produk</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-selling-price="{{ $product->selling_price }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Jumlah</label>
                        <input type="number" name="products[${productIndex}][quantity]" class="form-control quantity-input" min="1" value="1" required>
                    </div>
                    <div class="col-md-3">
                        <label>Harga Unit</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="text" class="form-control unit-price-display" value="0" readonly>
                            <input type="hidden" name="products[${productIndex}][unit_price]" class="unit-price-input" value="0">
                        </div>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger remove-product"><i class="fa fa-times"></i></button>
                    </div>
                </div>
            `;
            $('#product-list').append(productHtml);
            productIndex++;
        });

        $('#product-list').on('click', '.remove-product', function() {
            $(this).closest('.product-item').remove();
            updateTotal();
        });

        $('#product-list').on('input', '.quantity-input', function() {
            updateTotal();
        });
    });
</script>
@endpush
