@extends('layouts.admin.app')

@section('header')
<header class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Sosial & E-commerce</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">Sosial & E-commerce</li>
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
                <a href="{{ route('admin.cms.socials.create') }}" class="btn text-white" style="background-color: #9B4141;"><i class="fa fa-plus"></i> Tambah Sosial & E-commerce</a>
            </div>
        </div>
    </article>
    <article class="card-body">
        <table class="table table-striped table-bordered" id="datatable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Gambar</th>
                    <th>Tautan</th>
                    <th>Status</th>
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
                url: "{{ route('admin.cms.socials.data') }}",
                dataType: "json",
                type: "POST"
            },
            columns: [
                { data: 'no', name: 'no', className: "text-center align-middle"},
                { data: 'images', name: 'images', className: "align-middle" },
                { data: 'links', name: 'links', className: "align-middle" },
                { data: 'status', name: 'status', className: "align-middle" },
                { data: 'actions', name: 'actions', className: "align-middle", sortable: false },
            ]
        });
    });
</script>
@endpush
