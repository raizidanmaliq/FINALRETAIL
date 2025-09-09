@extends('layouts.admin.app')

@section('header')
<header class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Tambah Pemesanan Barang</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.purchase-orders.index') }}">Pemesanan Barang</a>
                </li>
                <li class="breadcrumb-item active">Tambah Pemesanan</li>
            </ol>
        </div>
    </div>
</header>
@endsection

@section('content')
<div class="content-body">
    <form id="form-purchase-order" method="POST" action="{{ route('admin.purchase-orders.store') }}">
        <div class="card">
            <div class="card-body">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="supplier_name">Nama Supplier</label>
                        <input type="text" name="supplier_name" class="form-control" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="order_date">Tanggal Pemesanan</label>
                        <input type="date" name="order_date" class="form-control" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="arrival_estimate_date">Estimasi Kedatangan Barang</label>
                        <input type="date" name="arrival_estimate_date" class="form-control">
                    </div>
                </div>

                <hr>

                <h4>Detail Produk</h4>
                <div id="product-list"></div>

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
<script>
    $(document).ready(function() {
        let productIndex = 0;
        const products = @json($products);

        function addProductRow() {
            const productHtml = `
                <div class="form-row product-row mt-3">
                    <div class="form-group col-md-4">
                        <label>Produk</label>
                        <select name="products[${productIndex}][product_id]" class="form-control" required>
                            <option value="">Pilih Produk</option>
                            ${products.map(p => `<option value="${p.id}">${p.name}</option>`).join('')}
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Jumlah</label>
                        <input type="number" name="products[${productIndex}][quantity]" class="form-control" min="1" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Harga Per Unit</label>
                        <input type="number" name="products[${productIndex}][unit_price]" class="form-control" min="0" required>
                    </div>
                    <div class="form-group col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger remove-product-btn w-100">
                            <i class="fa fa-trash"></i> Hapus
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
        });

        addProductRow();
    });
</script>
@endpush
