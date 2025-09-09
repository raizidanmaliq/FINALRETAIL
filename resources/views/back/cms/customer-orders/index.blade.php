@extends('layouts.admin.app')

@section('header')
<header class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Daftar Pesanan Pelanggan</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">Pesanan Pelanggan</li>
            </ol>
        </div>
    </div>
</header>
@endsection

@section('content')
<section class="card">
    <article class="card-header">
        <div class="float-right">
            <div class="btn-group">
                <a href="{{ route('admin.cms.customer-orders.create') }}" class="btn btn-success" style= "background-color: #9B4141;"><i class="fa fa-plus"></i> Buat Pesanan Baru
                </a>
            </div>
        </div>
    </article>
    <article class="card-body">

        <table class="table table-striped table-bordered" id="datatable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Kode Pesanan</th>
                    <th>Pelanggan</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                    <th>Tanggal Pesan</th>
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
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                url: "{{ route('admin.cms.customer-orders.data') }}",
                dataType: "json",
                type: "POST"
            },
            columns: [
                { data: 'id', name: 'id', className: "text-center align-middle"},
                { data: 'order_code', name: 'order_code', className: "align-middle" },
                { data: 'receiver_name', name: 'receiver_name', className: "align-middle" },
                { data: 'total_price', name: 'total_price', className: "align-middle" },
                { data: 'order_status', name: 'order_status', className: "align-middle" },
                { data: 'created_at', name: 'created_at', className: "align-middle" },
                { data: 'actions', name: 'actions', className: "align-middle", sortable: false, searchable: false },
            ]
        });

        // Hapus event listener modal lama
        // Event listener untuk tombol "Lihat Detail" yang baru
        $('#datatable').on('click', '.show-order', function(e) {
            e.preventDefault();
            const orderId = $(this).data('id');
            window.location.href = `{{ route('admin.cms.customer-orders.show', ':orderId') }}`.replace(':orderId', orderId);
        });
    });
</script>
@endpush
