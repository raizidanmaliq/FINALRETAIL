@extends('layouts.admin.app')

@section('header')
<header class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Daftar Supplier</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">Daftar Supplier</li>
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
                <a href="{{ route('admin.suppliers.create') }}" class="btn text-white" style="background-color: #9B4141;">
                    <i class="fa fa-plus"></i> Tambah Supplier
                </a>
            </div>
        </div>
    </article>
    <article class="card-body">
        <table class="table table-striped table-bordered" id="datatable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Supplier</th>
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
                url: "{{ route('admin.suppliers.data') }}",
                dataType: "json",
                type: "POST"
            },
            columns: [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    className: "text-center align-middle"
                },
                { data: 'name', name: 'name' },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    className: "text-center align-middle"
                }
            ]
        });
    });
</script>
@endpush
