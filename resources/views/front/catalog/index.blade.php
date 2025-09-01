@extends('layouts.common.app')

@section('content')
<div class="container my-5">
    {{-- Header Katalog dan Filter --}}
    <header class="text-center mb-5">
        <h1 class="fw-bold mb-2" style="font-size: 2.5rem; color: #A34A4A;">Katalog Produk Terbaru</h1>
        <p class="text-muted mb-4">Temukan berbagai pilihan produk berkualitas untuk kebutuhan Anda</p>

        {{-- Form Pencarian dan Filter --}}
        <form action="{{ route('front.catalog.index') }}" method="GET">
            {{-- Simpan semua parameter filter yang aktif --}}
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            @if(request('price'))
                <input type="hidden" name="price" value="{{ request('price') }}">
            @endif
            @if(request('sort'))
                <input type="hidden" name="sort" value="{{ request('sort') }}">
            @endif

            <div class="row justify-content-center mb-4">
                <div class="col-12 col-md-6 mb-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari produk..."
                                value="{{ request('search') }}" style="border-radius: 20px;">
                        <button type="submit" class="btn bg-white border-0" style="margin-left: -40px; z-index: 1;">
                            <i class="fas fa-search text-muted"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center g-3">
                {{-- Filter Kategori --}}
                <div class="col-md-3">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 20px;">
                            {{ request('category') ?: 'Semua kategori' }}
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['category' => null, 'page' => 1]) }}">Semua kategori</a></li>
                            @foreach($categories as $category)
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['category' => $category->name, 'page' => 1]) }}">{{ $category->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                {{-- Filter Harga --}}
                <div class="col-md-3">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 20px;">
                            @switch(request('price'))
                                @case('under-100k') Dibawah Rp 100.000 @break
                                @case('100k-200k') Rp 100.000 - Rp 200.000 @break
                                @default Semua Harga
                            @endswitch
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['price' => null, 'page' => 1]) }}">Semua Harga</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['price' => 'under-100k', 'page' => 1]) }}">Dibawah Rp 100.000</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['price' => '100k-200k', 'page' => 1]) }}">Rp 100.000 - Rp 200.000</a></li>
                        </ul>
                    </div>
                </div>

                {{-- Filter Urutan --}}
                <div class="col-md-3">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 20px;">
                            @switch(request('sort'))
                                @case('price_asc') Harga Terendah @break
                                @case('price_desc') Harga Tertinggi @break
                                @default Terbaru
                            @endswitch
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'newest', 'page' => 1]) }}">Terbaru</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'price_asc', 'page' => 1]) }}">Harga Terendah</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'price_desc', 'page' => 1]) }}">Harga Tertinggi</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </form>
    </header>


    {{-- Highlight Promo & Diskon --}}
    <section class="mb-5">
    <h3 class="fw-bold mb-4" style="color:#A34A4A;">
        <i class="fas fa-tags me-2"></i> Promo & Diskon Spesial
    </h3>
    {{-- Container untuk horizontal scroll --}}
    <div class="row flex-nowrap overflow-auto g-4 pb-3">
        @forelse($promoProducts as $product)
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="card product-card h-100 shadow-sm border-0 d-flex flex-column"
                 style="border-radius:15px; overflow:hidden; position:relative;">

                {{-- Badge kanan atas --}}
                @if($product->promo_label)
                <span class="position-absolute top-0 end-0 m-3 px-3 py-1 text-white fw-bold promo-badge"
                      style="background:#A34A4A; border-radius:20px; font-size:0.85rem; z-index:3;">
                    {{ $product->promo_label }}
                </span>
                @endif

                {{-- Icon Love & Cart tepat di bawah badge (kanan) --}}
                <div class="position-absolute d-flex flex-column gap-2 align-items-center"
                     style="right:15px; top:55px; z-index:3;">
                    <i class="fas fa-heart"
                       style="font-size:22px; color:#000; cursor:pointer;"></i>
                    <i class="fas fa-shopping-cart"
                       style="font-size:22px; color:#000; cursor:pointer;"></i>
                </div>

                {{-- Gambar Produk --}}
                <div style="height:250px; overflow:hidden;">
                    <img src="{{ asset($product->image) }}"
                         alt="{{ $product->name }}"
                         class="w-100 h-100 product-image"
                         style="object-fit:cover;">
                </div>

                {{-- Body Card --}}
                <div class="card-body d-flex flex-column text-center" style="background-color:#FDF7F7;">
                    <h6 class="fw-bold product-name">{{ $product->name }}</h6>
                    <p class="text-muted small flex-grow-1 product-description">
                        {{ Str::limit(strip_tags($product->description), 60) }}
                    </p>
                    <p class="fw-bold product-price" style="color:#A34A4A; font-size:1.2rem;">
                        Rp {{ number_format($product->selling_price,0,',','.') }}
                    </p>
                    {{-- Tombol Buy --}}
                    <button class="btn text-white mt-auto quick-view-btn"
                            data-bs-toggle="modal"
                            data-bs-target="#quickViewModal"
                            data-product-id="{{ $product->id }}"
                            style="background-color:#A34A4A; border-radius:30px; padding:10px;">
                        Buy
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <p class="text-center">Tidak ada produk promo saat ini.</p>
        </div>
        @endforelse
    </div>
</section>



    {{-- Grid Produk Utama --}}
    <section>
    <h3 class="fw-bold mb-4" style="color:#A34A4A;">Semua Produk</h3>
    <div class="row g-4 justify-content-start">
        @forelse($allProducts as $product)
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="card product-card h-100 shadow-sm border-0 d-flex flex-column position-relative"
                 style="border-radius:15px; overflow:hidden;">

                {{-- Badge kiri atas --}}
                @if($product->promo_label)
                <span class="position-absolute top-0 start-0 m-3 px-3 py-1 text-white fw-bold promo-badge"
                      style="background:#A34A4A; border-radius:20px; font-size:0.85rem;">
                    {{ $product->promo_label }}
                </span>
                @endif

                {{-- Icon Love & Cart di kanan tengah --}}
                {{-- Icon Love & Cart di kanan atas tapi agak turun --}}
<div class="position-absolute end-0 m-3 d-flex flex-column align-items-center gap-2"
     style="z-index:3; top: 15px;">
    <i class="fas fa-heart"
       style="font-size:22px; color:#000; cursor:pointer;"></i>
    <i class="fas fa-shopping-cart"
       style="font-size:22px; color:#000; cursor:pointer;"></i>
</div>


                {{-- Gambar Produk --}}
                <div style="height:250px; overflow:hidden;">
                    <img src="{{ asset($product->image) }}"
                         alt="{{ $product->name }}"
                         class="w-100 h-100 product-image"
                         style="object-fit:cover;">
                </div>

                {{-- Body Card --}}
                <div class="card-body d-flex flex-column text-center" style="background-color:#FDF7F7;">
                    <h6 class="fw-bold product-name">{{ $product->name }}</h6>
                    <p class="text-muted small flex-grow-1 product-description">
                        {{ Str::limit(strip_tags($product->description), 60) }}
                    </p>
                    <p class="fw-bold product-price" style="color:#A34A4A;">
                        Rp {{ number_format($product->selling_price,0,',','.') }}
                    </p>
                    {{-- Tombol Buy --}}
                    <button class="btn text-white mt-auto quick-view-btn"
                            data-bs-toggle="modal"
                            data-bs-target="#quickViewModal"
                            data-product-id="{{ $product->id }}"
                            style="background-color:#A34A4A; border-radius:30px; padding:10px;">
                        Buy
                    </button>
                </div>
            </div>
        </div>
        @empty
        <p class="text-center">Tidak ada produk yang cocok dengan filter Anda.</p>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center my-5">
        {{ $allProducts->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
    </div>
</section>

</div>

{{-- Modal Quick View --}}
<div class="modal fade" id="quickViewModal" tabindex="-1" aria-labelledby="quickViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 pt-0">
                <div class="row align-items-center">
                    <div class="col-md-6 mb-4 mb-md-0 text-center">
                        <img id="modal-product-image" src="" alt="Product Image" class="img-fluid rounded shadow-sm" style="max-height: 400px; object-fit: cover;">
                    </div>
                    <div class="col-md-6 d-flex flex-column">
                        <h2 id="modal-product-name" class="fw-bold mb-2" style="color:#A34A4A;"></h2>
                        <h4 class="mt-2 mb-3">
                            <span id="modal-product-price" class="fw-bold" style="color:#A34A4A; font-size: 1.8rem;"></span>
                        </h4>
                        <p class="text-muted small mb-4" id="modal-product-description"></p>
                        <p class="fw-bold mb-4" id="modal-product-stock" style="color: #6c757d;"></p>

                        {{-- Form untuk menambahkan ke keranjang --}}
                        <form id="add-to-cart-form" method="POST" action="">
                            @csrf
                            <input type="hidden" name="product_id" id="modal-product-id">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn text-white" style="background-color: #A34A4A; border-radius: 30px; padding: 12px 24px;">
                                    <i class="fas fa-shopping-cart me-2"></i> Beli Sekarang
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const quickViewModal = document.getElementById('quickViewModal');
    const addToCartForm = document.getElementById('add-to-cart-form');

    // Ketika modal dibuka
    quickViewModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const card = button.closest('.product-card');

        // Pastikan card ditemukan
        if (card) {
            const productName = card.querySelector('.product-name').textContent;
            const productPrice = card.querySelector('.product-price').textContent;
            const productImage = card.querySelector('.product-image').src;
            const productId = button.getAttribute('data-product-id');
            const productDescription = card.querySelector('.product-description') ? card.querySelector('.product-description').textContent : '';

            // Set isi modal
            document.getElementById('modal-product-image').src = productImage;
            document.getElementById('modal-product-name').textContent = productName;
            document.getElementById('modal-product-price').textContent = productPrice;
            document.getElementById('modal-product-description').textContent = productDescription;
            document.getElementById('modal-product-stock').textContent = 'Stok: Tersedia';
            document.getElementById('modal-product-id').value = productId;

            // Atur URL aksi form di sini, dan ini akan mengesampingkan `action` yang kosong.
            addToCartForm.action = `/customer/carts/${productId}/add`;
        }
    });

    // Handle form submission secara manual untuk memastikan rute sudah diatur
    addToCartForm.addEventListener('submit', function (e) {
        @if(!auth()->guard('customer')->check())
            e.preventDefault();
            window.location.href = "{{ route('customer.auth.login.index') }}";
        @endif
    });
});
</script>
@endpush
