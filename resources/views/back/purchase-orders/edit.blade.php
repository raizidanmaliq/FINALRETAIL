@extends('layouts.admin.app')

@section('header')
<header class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Edit Pemesanan Barang</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.purchase-orders.index') }}">Pemesanan Barang</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
    </div>
</header>
@endsection

@section('content')
<section class="card">
    <article class="card-body">
        <form action="{{ route('admin.purchase-orders.update', $purchaseOrder) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="supplier_name">Nama Supplier</label>
                <input type="text" name="supplier_name" id="supplier_name" class="form-control" value="{{ old('supplier_name', $purchaseOrder->supplier_name) }}" required>
            </div>

            <div class="form-group">
                <label for="order_date">Tanggal Pemesanan</label>
                <input type="date" name="order_date" id="order_date" class="form-control" value="{{ old('order_date', $purchaseOrder->order_date) }}" required>
            </div>

            <div class="form-group">
                <label for="arrival_estimate_date">Estimasi Tanggal Tiba</label>
                <input type="date" name="arrival_estimate_date" id="arrival_estimate_date" class="form-control" value="{{ old('arrival_estimate_date', $purchaseOrder->arrival_estimate_date) }}">
            </div>

            <div class="form-group">
                <label for="status">Status Pemesanan</label>
                <select name="status" id="status" class="form-control">
                    <option value="pending" {{ old('status', $purchaseOrder->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="on_delivery" {{ old('status', $purchaseOrder->status) == 'on_delivery' ? 'selected' : '' }}>On Delivery</option>
                    <option value="completed" {{ old('status', $purchaseOrder->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <hr>

            <h5>Detail Produk</h5>
            <div id="product-list">
                @foreach ($purchaseOrder->details as $index => $detail)
                <div class="form-row product-item mb-2">
                    <div class="col-md-5">
                        <select name="products[{{ $index }}][product_id]" class="form-control product-select" required>
                            <option value="">Pilih Produk</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" {{ $detail->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="products[{{ $index }}][quantity]" class="form-control" placeholder="Jumlah" value="{{ old('products.' . $index . '.quantity', $detail->quantity) }}" required>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="products[{{ $index }}][unit_price]" class="form-control" placeholder="Harga Unit" value="{{ old('products.' . $index . '.unit_price', $detail->unit_price) }}" step="0.01" required>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger remove-product"><i class="fa fa-trash"></i></button>
                    </div>
                </div>
                @endforeach
            </div>

            <button type="button" class="btn btn-primary my-3" id="add-product-btn">Tambah Produk</button>
            <br>
            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
        </form>
    </article>
</section>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const productList = document.getElementById('product-list');
        const addProductBtn = document.getElementById('add-product-btn');
        let productIndex = {{ $purchaseOrder->details->count() }}; // Mulai dari jumlah produk yang sudah ada

        function addProductRow() {
            const newRow = document.createElement('div');
            newRow.classList.add('form-row', 'product-item', 'mb-2');
            newRow.innerHTML = `
                <div class="col-md-5">
                    <select name="products[${productIndex}][product_id]" class="form-control product-select" required>
                        <option value="">Pilih Produk</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" name="products[${productIndex}][quantity]" class="form-control" placeholder="Jumlah" required>
                </div>
                <div class="col-md-3">
                    <input type="number" name="products[${productIndex}][unit_price]" class="form-control" placeholder="Harga Unit" step="0.01" required>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger remove-product"><i class="fa fa-trash"></i></button>
                </div>
            `;
            productList.appendChild(newRow);
            productIndex++;
        }

        addProductBtn.addEventListener('click', addProductRow);

        productList.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-product')) {
                e.target.closest('.product-item').remove();
            }
        });
    });
</script>
@endpush
