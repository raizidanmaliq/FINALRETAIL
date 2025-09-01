@extends('layouts.admin.app')

@section('header')
<header class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Testimoni Produk</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">Testimoni</li>
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
                <a href="{{ route('admin.cms.testimonials.create') }}" class="btn btn-success" style= "background-color: #9B4141;"><i class="fa fa-plus"></i> Tambah Testimoni</a>
            </div>
        </div>
    </article>
    <article class="card-body">
        <table class="table table-striped table-bordered" id="datatable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Pelanggan</th>
                    <th>Ulasan</th>
                    <th>Rating</th>
                    <th>Foto</th>
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
                url: "{{ route('admin.cms.testimonials.data') }}",
                dataType: "json",
                type: "POST"
            },
            columns: [
                { data: 'id', name: 'id', className: "text-center align-middle"},
                { data: 'customer_name', name: 'customer_name', className: "align-middle" },
                { data: 'review', name: 'review', className: "align-middle" },
                { data: 'rating', name: 'rating', className: "text-center align-middle" },
                { data: 'customer_photo', name: 'customer_photo', className: "text-center align-middle", sortable: false, searchable: false },
                { data: 'actions', name: 'actions', className: "text-center align-middle", sortable: false, searchable: false },
            ]
        });
    });
</script>
@endpush
