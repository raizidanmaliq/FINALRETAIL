@extends('layouts.admin.app')

@section('header')
<header class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Manajemen Katalog</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">Manajemen Katalog</li>
            </ol>
        </div>
    </div>
</header>
@endsection

@section('content')
<section class="card">
    <article class="card-body">
        <table class="table table-striped table-bordered" id="datatable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Gambar</th>
                    <th>Nama Produk</th>
                    <th>Harga Jual</th>
                    <th>Stok</th>
                    <th>Status Tampil</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </article>
</section>
@endsection

@push('js')
<script>
    $(function() {
        $('#datatable').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            pageLength: 10,
            pagingType: "simple_numbers",
            ajax: {
                url: "{{ route('admin.cms.catalog.data') }}",
                type: "GET"
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', className: "text-center align-middle", orderable: false, searchable: false },
                { data: 'image', name: 'image', className: "align-middle text-center", orderable: false, searchable: false },
                { data: 'name', name: 'name', className: "align-middle" },
                { data: 'selling_price', name: 'selling_price', className: "align-middle" },
                { data: 'stock', name: 'stock', className: "align-middle" },
                { data: 'status_display', name: 'is_displayed', className: "align-middle", orderable: false, searchable: false },
                { data: 'actions', name: 'actions', className: "align-middle text-center", orderable: false, searchable: false },
            ]
        });
    });
</script>
@endpush
