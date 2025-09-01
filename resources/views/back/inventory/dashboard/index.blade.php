@extends('layouts.admin.app')

@section('header')
<header class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Dashboard Stok</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">Dashboard Stok</li>
            </ol>
        </div>
    </div>
</header>
@endsection

@section('content')
<div class="content-body">
    {{-- Ringkasan Stok dalam Small Box --}}
    <div class="row">
        {{-- Total Stok --}}
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ number_format($data['total_stock'], 0, ',', '.') }}</h3>
                    <p>Total Stok</p>
                </div>
                <div class="icon">
                    <i class="fa fa-cubes"></i>
                </div>
            </div>
        </div>

        {{-- Total Jumlah Produk --}}
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format($data['total_products'], 0, ',', '.') }}</h3>
                    <p>Jumlah Produk</p>
                </div>
                <div class="icon">
                    <i class="fa fa-tags"></i>
                </div>
            </div>
        </div>

        {{-- Produk Stok Menipis --}}
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $data['low_stock_count'] }}</h3>
                    <p>Stok Menipis</p>
                </div>
                <div class="icon">
                    <i class="fa fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>

        {{-- Produk Stok Habis --}}
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $data['out_of_stock_count'] }}</h3>
                    <p>Stok Habis</p>
                </div>
                <div class="icon">
                    <i class="fa fa-times-circle"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Notifikasi Stok Menipis & Habis --}}
    @if($data['low_stock_count'] > 0)
        <div class="alert alert-warning">
            <i class="fa fa-exclamation-circle"></i> Ada <b>{{ $data['low_stock_count'] }}</b> produk dengan stok menipis.
        </div>
    @endif

    @if($data['out_of_stock_count'] > 0)
        <div class="alert alert-danger">
            <i class="fa fa-times-circle"></i> Ada <b>{{ $data['out_of_stock_count'] }}</b> produk yang stoknya habis.
        </div>
    @endif

    {{-- Grafik Pergerakan Stok --}}
    <div class="card shadow-sm">
        <div class="card-header text-white" style="background-color: #9B4141;"> 
            <h3 class="card-title">Grafik Pergerakan Stok 7 Hari Terakhir</h3>
        </div>
        <div class="card-body">
            <canvas id="stockMovementChart" height="150"></canvas>
        </div>
    </div>

    {{-- Shortcut Tambah Produk & Stock Opname --}}
    <div class="d-flex justify-content-end mt-3">
        <a href="{{ route('admin.inventory.products.create') }}" class="btn btn-primary btn-lg mr-2" style="background-color: #9B4141;">
            <i class="fa fa-plus"></i> Tambah Produk Baru
        </a>
        <a href="{{ route('admin.inventory.opname.index') }}" class="btn btn-warning btn-lg" style="background-color: #FBF3F3; border-color: #9B4141;">
            <i class="fa fa-edit"></i> Stock Opname
        </a>
    </div>

</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    const stockData = @json($data['stock_movement_data']);

    if (stockData && stockData.length > 0) {
        const labels = stockData.map(item => item.date);
        const stockIn = stockData.map(item => item.stock_in);
        const stockOut = stockData.map(item => item.stock_out);

        const ctx = document.getElementById('stockMovementChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Stok Masuk',
                        data: stockIn,
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.2)',
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Stok Keluar',
                        data: stockOut,
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.2)',
                        tension: 0.3,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: { usePointStyle: true }
                    }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }
});
</script>
@endpush
