@extends('layouts.admin.app')

@section('header')
<header class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>CTA Penutup</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">CTA Penutup</li>
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
                <a href="{{ route('admin.cms.ctas.create') }}" class="btn text-white" style="background-color: #9B4141;">
                    <i class="fa fa-plus"></i> Tambah CTA
                </a>
            </div>
        </div>
    </article>
    <article class="card-body">
        <table class="table table-striped table-bordered" id="datatable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Judul</th>
                    <th>Gambar</th>
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
                url: "{{ route('admin.cms.ctas.data') }}",
                dataType: "json",
                type: "POST"
            },
            columns: [
                { data: 'no', name: 'no', className: "text-center align-middle"},
                { data: 'title', name: 'title', className: "align-middle" },
                { data: 'image', name: 'image', className: "align-middle" },
                { data: 'status', name: 'status', className: "align-middle" },
                { data: 'action', name: 'action', className: "align-middle", sortable: false },
            ]
        });
    });
</script>
@endpush
