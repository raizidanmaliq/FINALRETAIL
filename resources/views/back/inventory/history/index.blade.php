@extends('layouts.admin.app')

@section('header')
<header class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Riwayat Produk</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">

                <li class="breadcrumb-item active">Riwayat Produk</li>
            </ol>
        </div>
    </div>
</header>
@endsection

@section('content')
<section class="card">
    <article class="card-header"></article>
    <article class="card-body">
        <table class="table table-striped table-bordered" id="datatable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Produk</th>
                    <th>Tipe</th>
                    <th>Stok Awal</th>
                    <th>Jumlah</th>
                    <th>Catatan</th>
                    <th>Tanggal</th>
                    <th>User</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mutations as $key => $mutation)
                    @php
                        $stok_awal = $mutation->type == 'in'
                                        ? $mutation->product->stock - $mutation->quantity
                                        : $mutation->product->stock + $mutation->quantity;
                    @endphp
                    <tr>
                        <td class="text-center align-middle">{{ $key + 1 }}</td>
                        <td class="align-middle">{{ $mutation->product->name }}</td>
                        <td class="align-middle">
                            @if ($mutation->type == 'in')
                                <span class="badge badge-success">Masuk</span>
                            @else
                                <span class="badge badge-danger">Keluar</span>
                            @endif
                        </td>
                        <td class="align-middle">{{ $stok_awal }}</td>
                        <td class="align-middle">{{ $mutation->quantity }}</td>
                        <td class="align-middle">{{ $mutation->note ?? '-' }}</td>
                        <td class="align-middle">{{ $mutation->created_at->format('d-m-Y H:i') }}</td>
                        <td class="align-middle">{{ $mutation->user->name ?? 'System' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </article>
</section>
@endsection

@push('js')
<script>
    $(function() {
        $('#datatable').DataTable({
            responsive: true,
            autoWidth: false,
            pageLength: 10,
            pagingType: "simple_numbers"
        });
    });
</script>
@endpush
