@extends('layouts.customer.app')

@section('header')
<header class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Riwayat Pesanan</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Riwayat Pesanan</li>
            </ol>
        </div>
    </div>
</header>
@endsection

@section('content')
<section class="card">
    <article class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <table class="table table-striped table-bordered" id="datatable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Kode Pesanan</th>
                    <th>Tanggal</th>
                    <th>Total Harga</th>
                    <th>Status Pesanan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $key => $order)
                <tr>
                    <td class="text-center">{{ $key+1 }}</td>
                    <td>{{ $order->order_code }}</td>
                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                    <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge
                            @if($order->order_status == 'pending') bg-warning text-dark
                            @elseif($order->order_status == 'processing') bg-info text-white
                            @elseif($order->order_status == 'shipped') bg-primary
                            @elseif($order->order_status == 'completed') bg-success
                            @endif">
                            {{ ucfirst($order->order_status) }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center">
                            @if($order->payment && $order->payment->proof_of_payment)
                            <a href="{{ Storage::url($order->payment->proof_of_payment) }}"
   class="btn btn-sm text-white me-2"
   style="background-color: #9B4141;" target="_blank">
    <i class="fas fa-file-image"></i> Bukti Bayar
</a>
                            @endif
                            <a href="{{ route('customer.orders.invoice', $order) }}"
   class="btn btn-sm text-white"
   style="background-color: #9B4141;" target="_blank">
    <i class="fas fa-file-invoice"></i> Nota
</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Anda belum memiliki riwayat pesanan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </article>
</section>
@endsection

@push('js')
<script>
    // aktifkan DataTable tanpa ajax
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
