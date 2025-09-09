@extends('layouts.common.app')

@section('header')
<header class="container-fluid py-3 border-bottom bg-white shadow-sm">
    <div class="row align-items-center">
        <div class="col-sm-6 d-flex align-items-center">
            <i class="fas fa-shopping-bag fs-3 text-danger me-2"></i>
            <h2 class="fw-bold mb-0 text-dark">Riwayat Pesanan</h2>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right mb-0 bg-transparent">
                <li class="breadcrumb-item">
                    <a href="{{ url('/') }}" class="text-decoration-none" style="color:#A34A4A;">Home</a>
                </li>
                <li class="breadcrumb-item active">Riwayat Pesanan</li>
            </ol>
        </div>
    </div>
</header>
@endsection

@section('content')
<div class="container my-5">

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-pill" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-pill" role="alert">
            <i class="fas fa-times-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Judul List --}}
    <div class="mb-4">
        <h4 class="fw-bold text-dark">Riwayat Pesanan Anda</h4>
    </div>

    {{-- Daftar Pesanan --}}
    @if($orders->isNotEmpty())
        @foreach($orders as $order)
        <div class="card shadow-lg border-0 rounded-4 mb-5 overflow-hidden">

            {{-- Header Pesanan --}}
            <div class="d-flex justify-content-between align-items-center bg-light px-4 py-3 border-bottom">
                <div>
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="fas fa-receipt me-2" style="color:#A34A4A;"></i> {{ $order->order_code }}
                    </h6>
                    <small class="text-muted">{{ $order->created_at->format('d M Y') }}</small>
                </div>
                <span class="badge rounded-pill px-3 py-2 fs-6
                    @if($order->order_status == 'pending') bg-warning text-dark
                    @elseif($order->order_status == 'processing') bg-info text-white
                    @elseif($order->order_status == 'shipped') bg-primary
                    @elseif($order->order_status == 'completed') bg-success
                    @else bg-secondary
                    @endif">
                    {{ ucfirst($order->order_status) }}
                </span>
            </div>

            {{-- Produk --}}
            <div class="card-body p-0">
                @foreach($order->items as $item)
                    <div class="d-flex align-items-center p-3">
                        <img src="{{ $item->product->images->first() ? asset($item->product->images->first()->image_path) : asset('front_assets/img/no-image.png') }}"
                             alt="{{ $item->product->name }}"
                             class="rounded me-3"
                             style="width:70px; height:70px; object-fit:cover;">

                        <div class="flex-grow-1">
                            <h6 class="fw-semibold mb-1">{{ $item->product->name }}</h6>
                            @if($item->color || $item->size)
                                <small class="text-muted d-block">
                                    Varian: {{ $item->color ?? '' }} {{ $item->size ? '/ '.$item->size : '' }}
                                </small>
                            @endif
                            <small class="text-muted">x{{ $item->quantity }}</small>
                        </div>

                        <div class="text-end">
                            <span class="fw-bold" style="color:#A34A4A;">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Total --}}
            <div class="px-4 py-3 bg-light d-flex justify-content-between align-items-center border-top border-bottom">
                <span class="fw-bold text-muted">
                    <i class="fas fa-shield-alt me-1" style="color:#A34A4A;"></i> Total Pesanan:
                </span>
                <span class="fw-bold fs-5" style="color:#A34A4A;">
                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                </span>
            </div>

            {{-- Tombol Aksi --}}
            <div class="px-4 py-3 d-flex justify-content-end gap-2 bg-white">
                <a href="{{ route('customer.orders.show', $order) }}"
                   class="btn btn-sm px-3 shadow-sm text-white"
                   style="background-color:#A34A4A; border-radius:8px;">
                   <i class="fas fa-eye me-1"></i> Detail
                </a>

                <a href="{{ route('customer.orders.invoice', $order) }}" target="_blank"
                   class="btn btn-sm px-3 shadow-sm text-white"
                   style="background-color:#A34A4A; border-radius:8px;">
                   <i class="fas fa-file-invoice me-1"></i> Nota
                </a>
            </div>
        </div>
        @endforeach
    @else
    <div class="card shadow-sm border-0 p-5 text-center rounded-4">
        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
        <h4 class="text-muted mb-3">Anda belum memiliki riwayat pesanan</h4>
        <a href="{{ route('front.catalog.index') }}" class="btn btn-lg px-4 text-white"
           style="background-color: #A34A4A; border-radius: 50px;">
            Mulai Belanja Sekarang
        </a>
    </div>
    @endif
</div>
@endsection
