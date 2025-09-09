@extends('layouts.admin.app')

@section('header')
<header class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Daftar Produk</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.inventory.dashboard') }}">Stok</a>
                </li>
                <li class="breadcrumb-item active">Daftar Produk</li>
            </ol>
        </div>
    </div>
</header>
@endsection

@section('content')
<section class="card">
    <article class="card-header">
    <div class="row">
        <div class="col-md-4">
            <label for="filter_stock" class="font-weight-bold">Filter Stok:</label>
            <select id="filter_stock" class="form-control">
                <option value="">Semua</option>
                <option value="low_stock">Stok Menipis</option>
                <option value="out_of_stock">Stok Habis</option>
            </select>
        </div>
        <div class="col-md-4">
            <label for="filter_category" class="font-weight-bold">Filter Kategori:</label>
            <select id="filter_category" class="form-control">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 text-right">
    <a href="{{ route('admin.inventory.products.create') }}"
       class="btn btn-danger mt-4"
       style="background-color: #9B4141; border-color: #9B4141;">
        <i class="fas fa-plus"></i> Tambah Produk
    </a>
</div>

    </div>
</article>


    <article class="card-body">
        <table class="table table-striped table-bordered" id="datatable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Produk</th>
                    <th>SKU/Barcode</th>
                    <th>Stok</th>
                    <th>Satuan</th>
                    <th>Harga Modal</th>
                    <th>Harga Jual</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </article>
</section>

<div class="modal fade" id="addStockModal" tabindex="-1" aria-labelledby="addStockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStockModalLabel">Tambah Stok Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addStockForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="product_id" id="add_stock_product_id">
                    <div class="form-group">
                        <label for="add_stock_product_name">Nama Produk</label>
                        <input type="text" class="form-control" id="add_stock_product_name" readonly>
                    </div>
                    <div class="form-group">
                        <label for="add_stock_current">Stok Sekarang</label>
                        <input type="text" class="form-control" id="add_stock_current" readonly>
                    </div>
                    <div class="form-group">
                        <label for="add_stock_quantity">Tambah Stok</label>
                        <input type="number" name="quantity" id="add_stock_quantity" class="form-control" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="correctStockModal" tabindex="-1" aria-labelledby="correctStockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="correctStockModalLabel">Koreksi Stok Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="correctStockForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="product_id" id="correct_stock_product_id">
                    <div class="form-group">
                        <label for="correct_stock_product_name">Nama Produk</label>
                        <input type="text" class="form-control" id="correct_stock_product_name" readonly>
                    </div>
                    <div class="form-group">
                        <label for="correct_stock_current">Stok Sistem</label>
                        <input type="text" class="form-control" id="correct_stock_current" readonly>
                    </div>
                    <div class="form-group">
                        <label for="correct_stock_new">Input Stok Fisik Baru</label>
                        <input type="number" name="stock" id="correct_stock_new" class="form-control" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="correct_stock_note">Alasan Koreksi</label>
                        <textarea name="note" id="correct_stock_note" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Koreksi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(function() {
        const datatable = $('#datatable').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            pageLength: 10,
            pagingType: "simple_numbers",
            order: [],
            ajax: {
                url: "{{ route('admin.inventory.products.data') }}",
                type: "GET",
                data: function(d) {
                    d.filter_stock = $('#filter_stock').val();
                    d.filter_category = $('#filter_category').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', className: "text-center align-middle", orderable: false, searchable: false },
                { data: 'name', name: 'name', className: "align-middle" },
                { data: 'sku', name: 'sku', className: "align-middle text-center" },
                { data: 'stock', name: 'stock', className: "align-middle text-center font-weight-bold" },
                { data: 'unit', name: 'unit', className: "align-middle text-center" },
                { data: 'cost_price', name: 'cost_price', className: "align-middle text-right" },
                { data: 'selling_price', name: 'selling_price', className: "align-middle text-right" },
                { data: 'actions', name: 'actions', className: "align-middle text-center", orderable: false, searchable: false },
            ]
        });

        $('#filter_stock, #filter_category').on('change', function() {
            datatable.ajax.reload();
        });

        // Toast config biar sama kaya edit
        function showToast(type, message) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: type,
                title: message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        }

        // Add Stock Modal Logic
        $('#datatable').on('click', '.btn-add-stock', function() {
            const data = datatable.row($(this).parents('tr')).data();
            $('#add_stock_product_id').val(data.id);
            $('#add_stock_product_name').val(data.name);
            $('#add_stock_current').val(data.stock);
            $('#addStockModal').modal('show');
        });

        $('#addStockForm').on('submit', function(e) {
            e.preventDefault();
            const productId = $('#add_stock_product_id').val();
            $.ajax({
                url: `{{ url('admin/inventory/products') }}/${productId}/add-stock`,
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#addStockModal').modal('hide');
                    datatable.ajax.reload();
                    showToast('success', response.message || 'Stok berhasil ditambahkan.');
                },
                error: function(response) {
                    let errors = response.responseJSON.errors;
                    let errorMessage = 'Gagal menambahkan stok.';
                    if (errors) {
                        errorMessage += ' ' + Object.values(errors).join(', ');
                    }
                    showToast('error', errorMessage);
                    console.error(response);
                }
            });
        });

        // Correct Stock Modal Logic
        $('#datatable').on('click', '.btn-correct-stock', function() {
            const data = datatable.row($(this).parents('tr')).data();
            $('#correct_stock_product_id').val(data.id);
            $('#correct_stock_product_name').val(data.name);
            $('#correct_stock_current').val(data.stock);
            $('#correctStockModal').modal('show');
        });

        $('#correctStockForm').on('submit', function(e) {
            e.preventDefault();
            const productId = $('#correct_stock_product_id').val();
            $.ajax({
                url: `{{ url('admin/inventory/products') }}/${productId}/correct-stock`,
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#correctStockModal').modal('hide');
                    datatable.ajax.reload();
                    showToast('success', response.message || 'Stok berhasil dikoreksi.');
                },
                error: function(response) {
                    let errors = response.responseJSON.errors;
                    let errorMessage = 'Gagal mengoreksi stok.';
                    if (errors) {
                        errorMessage += ' ' + Object.values(errors).join(', ');
                    }
                    showToast('error', errorMessage);
                    console.error(response);
                }
            });
        });
    });
</script>
@endpush
