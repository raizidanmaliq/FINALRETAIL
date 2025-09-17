@extends('layouts.common.app')

@section('content')
<main>
    {{-- Hero Section --}}
    @if(isset($hero) && !empty($hero->images))
<section class="hero-section py-5 position-relative overflow-hidden"
         style="background: linear-gradient(135deg, #fff 0%, #faf7f7 100%);">

    {{-- Decorative Shape Background --}}
    <div class="position-absolute top-0 start-0 w-100 h-100"
         style="background: url('{{ asset('images/wave.png') }}') no-repeat bottom center / cover;
                opacity: 0.2; z-index:0;">
    </div>

    <div class="container position-relative" style="z-index:1;">
        <div class="row align-items-center g-5">

            {{-- Left: Carousel --}}
            <div class="col-lg-6">
                <div id="heroCarousel" class="carousel slide carousel-fade shadow-lg rounded-4 overflow-hidden" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach ($hero->images as $imagePath)
                            <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                <img src="{{ asset($imagePath) }}" class="d-block w-100"
                                     alt="{{ $hero->headline }}"
                                     style="object-fit: cover; height: 400px;">
                            </div>
                        @endforeach
                    </div>
                   @if(count($hero->images) > 1)
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon rounded-circle p-2" aria-hidden="true"
              style="background-color:#9B4141;"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon rounded-circle p-2" aria-hidden="true"
              style="background-color:#9B4141;"></span>
        <span class="visually-hidden">Next</span>
    </button>
@endif

                </div>
            </div>

            {{-- Right: Headline --}}
            <div class="col-lg-6 text-center text-lg-start">
                <h1 class="display-4 fw-bold mb-3"
    style="font-family: 'Playfair Display', serif; color:#9B4141;">
    {{ $hero->headline }}
</h1>

                <p class="fs-5 text-muted mb-4">
                    {{ $hero->subheadline }}
                </p>
                <a href="{{ route('front.catalog.index') }}"
                   class="btn btn-lg px-4 py-3"
                   style="background-color: #9B4141; border:none; border-radius: 12px; color:#fff; font-weight:600; transition:all 0.3s;">
                    Belanja Sekarang
                </a>
            </div>

        </div>
    </div>
</section>
@endif


    {{-- Best Seller --}}
    <section id="best-seller" class="best-seller-section py-5">
    <div class="container">
        <h2 class="text-center" style="color: #A34A4A;">Best Seller</h2>
        <p class="text-center text-muted">The Most Popular Choices Right Now</p>

        <!-- Tombol lihat semua -->
        <div class="text-center" style="margin: 2rem 0;">
            <a href="{{ route('front.catalog.index') }}"
               class="fw-bold"
               style="border:1px solid #A34A4A; color:#A34A4A; font-size:0.9rem; text-decoration:none; padding:6px 14px; border-radius:6px; display:inline-block; transition:all 0.3s;">
               Lihat Semua →
            </a>
        </div>

        <div class="row">
            @forelse($bestSellerProducts as $product)
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm border-0 product-card">
                    <div class="position-relative">
                        <div style="height: 250px; overflow: hidden;">
                            @if($product->images && count($product->images) > 0)
                                <img src="{{ asset($product->images[0]->image_path) }}"
                                     class="card-img-top product-image w-100 h-100 object-fit-cover"
                                     alt="{{ $product->name }}">
                            @endif
                        </div>
                        @if($product->promo_label)
                            <span class="position-absolute top-0 end-0 m-2 px-2 py-1 text-white fw-bold"
                                  style="background:#A34A4A; border-radius:6px; font-size:0.85rem;">
                                {{ $product->promo_label }}
                            </span>
                        @endif
                    </div>
                    <div class="card-body d-flex flex-column text-center">
                        <h6 class="card-title fw-bold mb-1">{{ $product->name }}</h6>
                        <p class="card-text fw-bold mb-2" style="color:#A34A4A;">
                            Rp. {{ number_format($product->selling_price, 0, ',', '.') }}
                        </p>
                        <!-- Tombol Buy -->
                        <button class="btn w-100 mt-auto quick-view-btn"
                                style="background:#A34A4A; color:#fff; border-radius:6px; padding:8px 0;"
                                data-bs-toggle="modal"
                                data-bs-target="#quickViewModal"
                                data-product-id="{{ $product->id }}">
                            Belanja Sekarang
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <p class="text-center">Tidak ada produk best seller saat ini.</p>
            @endforelse
        </div>
    </div>
</section>

    {{-- Flash Sale Section --}}
    <section id="flash-sale" class="flash-sale-section py-5" style="background:#fff;">
    <div class="container">
        <div class="row g-4">

            {{-- Kolom Kiri: Flash Sale --}}
            {{-- Hapus 'align-items-stretch' dari row induk --}}
            <div class="col-lg-6 d-flex flex-column" style="min-height: 400px;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold" style="color:#A34A4A; font-size:1.25rem;">FLASH SALE</h5>
                    <span id="flash-sale-timer" class="px-3 py-1 text-white fw-bold rounded"
                        style="background:#A34A4A; font-size:0.85rem;">
                        00:00:00
                    </span>
                    <a href="{{ route('front.catalog.index') }}"
                       class="fw-bold d-inline-block"
                       style="border:1px solid #A34A4A; color:#A34A4A; font-size:0.9rem; text-decoration:none; padding:6px 14px; border-radius:6px;">
                        Lihat Semua →
                    </a>
                </div>
                <div class="row g-3 flex-grow-1">
                    @forelse($flashSaleProducts as $product)
                        <div class="col-6">
                            <div class="card h-100 shadow-sm border-0 rounded-3 text-center product-card">
                                <div class="position-relative" style="height:200px; overflow:hidden;">
                                    @if($product->images && count($product->images) > 0)
                                        <img src="{{ asset($product->images[0]->image_path) }}"
                                             class="card-img-top product-image w-100 h-100 object-fit-cover"
                                             alt="{{ $product->name }}">
                                    @endif
                                    <span class="position-absolute top-0 end-0 m-2 px-2 py-1 text-white fw-bold"
                                          style="background:#A34A4A; border-radius:6px; font-size:0.7rem;">
                                        FLASH SALE
                                    </span>
                                </div>
                                <div class="p-3 d-flex flex-column">
                                    <h6 class="fw-bold mb-1 product-name" style="font-size:0.95rem;">
                                        {{ $product->name }}
                                    </h6>
                                    <p class="fw-bold mb-3 product-price" style="color:#A34A4A; font-size:0.95rem;">
                                        Rp. {{ number_format($product->selling_price, 0, ',', '.') }}
                                    </p>
                                    <button class="btn w-100 text-white mt-auto quick-view-btn"
                                            style="background:#A34A4A; border-radius:8px; font-size:0.85rem;"
                                            data-bs-toggle="modal"
                                            data-bs-target="#quickViewModal"
                                            data-product-id="{{ $product->id }}">
                                        Belanja Sekarang
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center col-12">Tidak ada produk flash sale saat ini.</p>
                    @endforelse
                </div>
            </div>

            {{-- Kolom Kanan: Banner Carousel Dinamis --}}
            <div class="col-lg-6 d-flex">
                <div id="bannerCarousel" class="carousel slide w-100 h-100" data-bs-ride="carousel">
                    {{-- Atur tinggi wadah banner agar konsisten --}}
                    <div class="carousel-inner h-100 rounded-3" style="min-height: 400px; max-height: 400px; overflow: hidden;">
                        @if($banners->count() > 0)
                            @foreach($banners as $key => $banner)
                                <div class="carousel-item {{ $key == 0 ? 'active' : '' }} h-100">
                                    @php
                                        $cleanedPath = str_replace('\\', '/', $banner->image);
                                        $finalPath = Str::startsWith($cleanedPath, 'back_assets') ? $cleanedPath : 'storage/' . $cleanedPath;
                                    @endphp
                                    <a href="{{ $banner->link }}" target="_blank" class="w-100 h-100 d-block">
                                        <img src="{{ asset($finalPath) }}" alt="{{ $banner->title }}"
                                             class="d-block w-100 h-100"
                                             style="object-fit: fill; border-radius:1rem;">
                                    </a>
                                </div>
                            @endforeach
                        @else
                            <div class="card bg-light p-5 border-0 shadow-sm text-center w-100 h-100 d-flex align-items-center justify-content-center">
                                <h4 class="text-muted">Promo menarik segera hadir!</h4>
                            </div>
                        @endif
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: #A34A4A; border-radius: 50%;"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true" style="background-color: #A34A4A; border-radius: 50%;"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
{{-- Highlight Keunggulan --}}
    <section class="py-5" style="background:#f8f9fa;">
    <div class="container text-center">
        {{-- Judul --}}
        <h2 class="fw-bold mb-5" style="color:#9B4141;">Mengapa Memilih Kami?</h2>

        <div class="row justify-content-center">
            {{-- Produk Original --}}
            <div class="col-md-3 mb-4">
                <i class="fas fa-tshirt fa-3x mb-3" style="color: #9B4141;"></i>
                <h5 class="fw-bold" style="color: #9B4141;">Produk Original</h5>
                <p class="text-muted medium">Kami menjamin produk yang selalu baru dan berkualitas.</p>
            </div>

            {{-- Harga Kompetitif --}}
            <div class="col-md-3 mb-4">
                <i class="fas fa-tags fa-3x mb-3" style="color: #9B4141;"></i>
                <h5 class="fw-bold" style="color: #9B4141;">Harga Kompetitif</h5>
                <p class="text-muted medium">Dapatkan harga terbaik di pasar tanpa mengorbankan kualitas.</p>
            </div>

            {{-- Garansi Uang Kembali --}}
            <div class="col-md-3 mb-4">
                <i class="fas fa-money-bill-wave fa-3x mb-3" style="color: #9B4141;"></i>
                <h5 class="fw-bold" style="color: #9B4141;">Garansi Uang Kembali</h5>
                <p class="text-muted medium">Jika tidak puas, uang Anda akan kami kembalikan.</p>
            </div>

            {{-- Pengiriman Cepat --}}
            <div class="col-md-3 mb-4">
                <i class="fas fa-shipping-fast fa-3x mb-3" style="color: #9B4141;"></i>
                <h5 class="fw-bold" style="color: #9B4141;">Pengiriman Cepat</h5>
                <p class="text-muted medium">Pesanan Anda akan sampai tepat waktu dan aman.</p>
            </div>
        </div>
    </div>
</section>

    <section class="py-5"
    style="background: url('{{ asset('images/image2.png') }}') no-repeat center center/cover;">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="fw-bold" style="color: #9B4141;">Our Fashion Story</h2>
            <p class="text-muted">
                Dari sebuah passion untuk gaya yang berkelas, kami menciptakan pakaian yang membuat Anda merasa istimewa
            </p>
        </div>

        <div class="row align-items-stretch">
            {{-- Foto Founder --}}
            <div class="col-md-4 mb-3 d-flex">
                <div class="card border-0 shadow-sm h-100 w-100">
                    <img src="{{ asset('images/image4.png') }}" class="card-img-top" alt="Founder">
                    <div class="card-body d-flex flex-column">
                        <h6 class="fw-bold mb-1">Sarah Wijaya</h6>
                        <p class="text-muted mb-2">Founder & Creative Director</p>
                        <p class="mb-0">
                            Kami percaya bahwa desain yang baik tidak hanya tentang estetika,
                            tetapi tentang menciptakan pengalaman yang bermakna bagi setiap pengguna.
                        </p>
                        {{-- Spacer agar teks mepet bawah --}}
                        <div class="mt-auto"></div>
                    </div>
                </div>
            </div>

            {{-- Foto Tim --}}
            <div class="col-md-8 mb-3 d-flex">
                <img src="{{ asset('images/image3.png') }}" class="img-fluid rounded shadow h-100 w-100 object-fit-cover" alt="Tim Kami">
            </div>
        </div>
    </div>
</section>

    <section class="py-5">
    <div class="container">
        <h2 class="text-center fw-bold mb-5" style="color:#A34A4A;">
            Perjalanan Kami
        </h2>

        <div class="position-relative">
            <div class="position-absolute top-0 bottom-0 start-0 ms-2 border-start border-2" style="border-color:#A34A4A;"></div>

            <div class="d-flex mb-4">
                <div class="me-3 mt-2">
                    <span class="d-inline-block rounded-circle" style="width:12px;height:12px;background:#A34A4A;"></span>
                </div>
                <div class="card border rounded-3 shadow-sm w-100" style="border-color:#A34A4A;">
                    <div class="card-body">
                        <h6 class="fw-bold" style="color:#A34A4A;">2019 Awal Mula</h6>
                        <p class="mb-0 small">
                            Dimulai dari sebuah kafe kecil di Jakarta, Sarah dan dua rekannya memutuskan untuk
                            mengubah passion mereka terhadap desain menjadi sebuah bisnis yang dapat membantu UMKM lokal.
                        </p>
                    </div>
                </div>
            </div>

            <div class="d-flex mb-4">
                <div class="me-3 mt-2">
                    <span class="d-inline-block rounded-circle" style="width:12px;height:12px;background:#A34A4A;"></span>
                </div>
                <div class="card border rounded-3 shadow-sm w-100" style="border-color:#A34A4A;">
                    <div class="card-body">
                        <h6 class="fw-bold" style="color:#A34A4A;">2020 Momentum Pertama</h6>
                        <p class="mb-0 small">
                            Pandemi justru menjadi berkah tersembunyi. Banyak bisnis yang membutuhkan transformasi digital,
                            dan kami siap membantu dengan solusi desain yang terjangkau namun berkualitas.
                        </p>
                    </div>
                </div>
            </div>

            <div class="d-flex mb-4">
                <div class="me-3 mt-2">
                    <span class="d-inline-block rounded-circle" style="width:12px;height:12px;background:#A34A4A;"></span>
                </div>
                <div class="card border rounded-3 shadow-sm w-100" style="border-color:#A34A4A;">
                    <div class="card-body">
                        <h6 class="fw-bold" style="color:#A34A4A;">2023 Awal Kemajuan</h6>
                        <p class="mb-0 small">
                            Tim berkembang menjadi 15 orang talenta terbaik dari berbagai disiplin ilmu. Kami pindah ke studio
                            yang lebih besar dan mulai mengerjakan proyek-proyek enterprise.
                        </p>
                    </div>
                </div>
            </div>

            <div class="d-flex">
                <div class="me-3 mt-2">
                    <span class="d-inline-block rounded-circle" style="width:12px;height:12px;background:#A34A4A;"></span>
                </div>
                <div class="card border rounded-3 shadow-sm w-100" style="border-color:#A34A4A;">
                    <div class="card-body">
                        <h6 class="fw-bold" style="color:#A34A4A;">2025 Bertahan</h6>
                        <p class="mb-0 small">
                            Dengan lebih dari 150 proyek sukses, kami kini fokus pada inovasi dan sustainability dalam desain,
                            sambil terus mempertahankan nilai-nilai human-centered design.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Penilaian Produk --}}
    <section id="product-reviews" class="py-4" style="background:#fff;">
        <div class="container">
            {{-- Judul --}}
            <h5 class="fw-bold mb-3" style="color:#A34A4A;">Penilaian Produk</h5>

            {{-- Summary Rating --}}
            <div class="border rounded p-3 mb-4">
                <div class="d-flex align-items-center mb-3">
                    <h3 class="fw-bold me-3 mb-0" style="color:#000;">
                        {{ number_format($averageRating, 1) }} dari 5
                    </h3>
                    <div>
                        @php
                            $rating = round($averageRating);
                        @endphp
                        @for ($i = 0; $i < 5; $i++)
                            @if ($rating > $i)
                                <span class="text-warning fs-5">★</span>
                            @else
                                <span class="text-muted fs-5">★</span>
                            @endif
                        @endfor
                    </div>
                </div>

                {{-- Filter Buttons (dinamis) --}}
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('front.home.index') }}" class="btn btn-light btn-sm border">Semua ({{ $totalReviews }})</a>
                    @for ($i = 5; $i >= 1; $i--)
                        @php
                            $count = App\Models\Cms\Testimonial::where('rating', $i)->count();
                        @endphp
                        <a href="{{ route('front.home.index', ['rating' => $i]) }}" class="btn btn-light btn-sm border">{{ $i }} Bintang ({{ number_format($count, 0, ',', '.') }})</a>
                    @endfor
                </div>
            </div>

            {{-- Review Card --}}
            @forelse($testimonials as $testimonial)
            <div class="mb-4 pb-3 border-bottom">
                {{-- User Info --}}
                <div class="d-flex align-items-center mb-2">
                    @if($testimonial->customer_photo)
                        <img src="{{ asset($testimonial->customer_photo) }}"
                             alt="{{ $testimonial->customer_name }}"
                             class="rounded-circle me-2"
                             style="width:40px; height:40px; object-fit:cover;">
                    @else
                        <div class="rounded-circle me-2 d-flex align-items-center justify-content-center bg-secondary"
                             style="width:40px; height:40px;">
                            <i class="fas fa-user text-white"></i>
                        </div>
                    @endif
                    <div>
                        <h6 class="fw-bold mb-0">{{ $testimonial->customer_name }}</h6>
                        <small class="text-muted">{{ \Carbon\Carbon::parse($testimonial->order_date)->format('d-m-Y') }}</small>
                    </div>
                </div>

                {{-- Rating --}}
                <div class="mb-1">
                    @for ($i = 0; $i < 5; $i++)
                        @if ($testimonial->rating > $i)
                            <span class="text-warning">★</span>
                        @else
                            <span class="text-muted">★</span>
                        @endif
                    @endfor
                </div>

        {{-- Tampilkan Nama Pesanan --}}
        <p class="mb-2">{{ $testimonial->product_name ?? '-' }}</p>

                {{-- Isi Review --}}
                <p class="mb-2">
                    {{ strip_tags($testimonial->review) }}
                </p>



                {{-- Foto/Media (jika ada) --}}
                @if($testimonial->media)
                <div class="d-flex gap-2">
                    @foreach(json_decode($testimonial->media) as $mediaPath)
                        <div class="border rounded overflow-hidden" style="width:80px; height:100px;">
                            <img src="{{ asset($mediaPath) }}" class="w-100 h-100 object-fit-cover">
                        </div>
                    @endforeach
                </div>
                @endif
            </div>
            @empty
            <p class="text-center text-muted">Belum ada penilaian produk saat ini.</p>
            @endforelse

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $testimonials->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </section>

    <section class="cara-pemesanan py-5">
    <div class="container text-center">
        <h2 class="mb-5 fw-bold" style="color: #A34A4A;">Cara Pemesanan</h2>
        <div class="card p-4 shadow-sm" style="border: 2px solid #A34A4A; border-radius: 12px;">
            <div class="row align-items-center">

                {{-- Bagian langkah-langkah --}}
                <div class="col-md-6 mb-4 mb-md-0">
                    <div class="d-flex flex-wrap align-items-center justify-content-center">

                        {{-- Step 1 --}}
                        <div class="text-center mx-3 mb-3" style="min-width:100px;">
                            <div class="rounded-circle d-flex justify-content-center align-items-center mb-2"
                                 style="width:80px; height:80px; border:2px solid #A34A4A; margin:auto;">
                                <i class="fas fa-box fa-2x" style="color: #9B4141;"></i>
                            </div>
                            <p class="fw-bold m-0">1. Pilih Produk</p>
                        </div>

                        <i class="fas fa-arrow-right fa-2x mx-2" style="color: #9B4141;"></i>

                        {{-- Step 2 --}}
                        <div class="text-center mx-3 mb-3" style="min-width:100px;">
                            <div class="rounded-circle d-flex justify-content-center align-items-center mb-2"
                                 style="width:80px; height:80px; border:2px solid #A34A4A; margin:auto;">
                                <i class="fas fa-shopping-cart fa-2x" style="color: #9B4141;"></i>
                            </div>
                            <p class="fw-bold m-0">2. Checkout</p>
                        </div>

                        <i class="fas fa-arrow-right fa-2x mx-2" style="color: #9B4141;"></i>

                        {{-- Step 3 --}}
                        <div class="text-center mx-3 mb-3" style="min-width:100px;">
                            <div class="rounded-circle d-flex justify-content-center align-items-center mb-2"
                                 style="width:80px; height:80px; border:2px solid #A34A4A; margin:auto;">
                                <i class="fas fa-truck-moving fa-2x" style="color: #9B4141;"></i>
                            </div>
                            <p class="fw-bold m-0">3. Kirim</p>
                        </div>

                        <i class="fas fa-arrow-right fa-2x mx-2" style="color: #9B4141;"></i>

                        {{-- Step 4 --}}
                        <div class="text-center mx-3 mb-3" style="min-width:100px;">
                            <div class="rounded-circle d-flex justify-content-center align-items-center mb-2"
                                 style="width:80px; height:80px; border:2px solid #A34A4A; margin:auto;">
                                <i class="fas fa-home fa-2x" style="color: #9B4141;"></i>
                            </div>
                            <p class="fw-bold m-0">4. Terima di Rumah</p>
                        </div>

                    </div>
                </div>

                {{-- Bagian thumbnail video --}}
                <div class="col-md-6 text-center">
                    <div class="position-relative d-inline-block" style="max-width:100%; cursor:pointer;">
                        <a href="https://www.youtube.com/watch?v=VIDEO_ID" target="_blank">
                            {{-- Thumbnail image --}}
                            <img src="{{ asset('images/image5.png') }}"
                                 alt="Tutorial Cara Order"
                                 class="img-fluid rounded shadow"
                                 style="max-height:350px; width:100%; object-fit:cover;">
                            {{-- Overlay Play Button --}}
                            <div class="position-absolute top-50 start-50 translate-middle">
                                <div class="d-flex justify-content-center align-items-center rounded-circle"
                                     style="width:70px; height:70px; background:rgba(204,51,51,0.9);">
                                    <i class="fas fa-play fa-lg text-white ms-1"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
    </section>

    <section class="keamanan-jaminan py-5">
    <div class="container text-center">
        {{-- Judul --}}
        <h2 class="mb-2 fw-bold" style="color: #A34A4A;">Keamanan dan Jaminan</h2>
        <p class="mb-5" style="color: #666;">
            Kami memberikan perlindungan dan jaminan penuh untuk setiap transaksi Anda
        </p>

        <div class="row g-4">
            {{-- Keamanan Transaksi --}}
            <div class="col-md-6">
                <div class="card p-4 h-100 shadow-sm border-0">
                    <div class="d-flex justify-content-center align-items-center mb-3">
                        <i class="fas fa-certificate fa-3x" style="color: #A34A4A;"></i>
                    </div>
                    <h5 class="fw-bold" style="color:#A34A4A;">Keamanan Transaksi</h5>
                    <ul class="list-unstyled text-start mt-3">
                        <li><i class="fas fa-check-circle me-2" style="color: #A34A4A;"></i> Sistem pembayaran terenkripsi dengan teknologi SSL 256-bit.</li>
                        <li><i class="fas fa-check-circle me-2" style="color: #A34A4A;"></i> Data pribadi Anda 100% tidak dibagikan ke pihak ketiga.</li>
                        <li><i class="fas fa-check-circle me-2" style="color: #A34A4A;"></i> Transaksi diawasi oleh sistem keamanan real-time.</li>
                    </ul>
                </div>
            </div>

            {{-- Garansi Produk --}}
            <div class="col-md-6">
                <div class="card p-4 h-100 shadow-sm border-0">
                    <div class="d-flex justify-content-center align-items-center mb-3">
                        <i class="fas fa-shield-alt fa-3x" style="color: #A34A4A;"></i>
                    </div>
                    <h5 class="fw-bold" style="color:#A34A4A;">Garansi Produk</h5>
                    <ul class="list-unstyled text-start mt-3">
                        <li><i class="fas fa-check-circle me-2" style="color: #A34A4A;"></i> Sistem pembayaran terenkripsi dengan teknologi SSL 256-bit.</li>
                        <li><i class="fas fa-check-circle me-2" style="color: #A34A4A;"></i> Data pribadi Anda 100% tidak dibagikan ke pihak ketiga.</li>
                        <li><i class="fas fa-check-circle me-2" style="color: #A34A4A;"></i> Transaksi diawasi oleh sistem keamanan real-time.</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Pembayaran --}}
        <div class="pembayaran-section mt-5">
            <h5 class="fw-bold mb-4" style="color:#A34A4A;">Menerima Pembayaran :</h5>
            <div class="d-flex justify-content-center align-items-center flex-wrap">
                <div class="mx-4 text-center">
                    <i class="fas fa-qrcode fa-3x" style="color: #A34A4A;"></i>
                    <p class="fw-bold mt-2">QRIS</p>
                </div>
                <div class="mx-4 text-center">
                    <i class="fas fa-university fa-3x" style="color: #A34A4A;"></i>
                    <p class="fw-bold mt-2">Transfer BANK</p>
                </div>
                <div class="mx-4 text-center">
                    <i class="fas fa-wallet fa-3x" style="color: #A34A4A;"></i>
                    <p class="fw-bold mt-2">E-Wallet</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="pesan-sekarang py-5">
    <div class="container">
        <h2 class="text-center fw-bold mb-2" style="color: #A34A4A; font-size: clamp(1.5rem, 4vw, 1.8rem);">
            Pesan Sekarang, Tampil Percaya Diri
        </h2>
        <p class="text-center mb-5" style="color: #666; font-size: clamp(0.9rem, 2.5vw, 1rem);">
            Dapatkan penawaran terbaik kami melalui media sosial dan e-commerce favorit Anda.
        </p>

        @if($social)
            <div class="row g-4 align-items-stretch">

                {{-- Media Sosial --}}
                <div class="col-md-6 d-flex">
                    <div class="w-100 card border-0 shadow-sm p-4 h-100">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
                            <div class="mb-3 mb-md-0">
                                <h4 class="h5 fw-bold m-0" style="color: #A34A4A;">Media Sosial</h4>
                                <p class="m-0" style="color: #666; font-size: 0.95rem;">
                                    Lihat lebih banyak outfit dan promo menarik di sini!
                                </p>
                            </div>
                            <a href="{{ $social->button_link }}"
   class="btn fw-semibold text-white px-4 py-2"
   style="background: linear-gradient(135deg,#A34A4A); border:none; border-radius:8px;">
    {{ $social->button_text }}
</a>

                        </div>

                        <div class="row g-2 mt-2">
                            @foreach($social->images as $imagePath)
                                <div class="col-6 col-md-4">
    <div class="rounded overflow-hidden border border-2"
         style="border-color:#A34A4A; width: 100%; height: 180px;">
        <img src="{{ asset($imagePath) }}"
             class="img-fluid w-100 h-100"
             style="object-fit: cover;"
             alt="Social Media Post">
    </div>
</div>

                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- E-commerce --}}
                <div class="col-md-6 d-flex">
                    <div class="card w-100 p-4 text-center text-white shadow-sm border-0 h-100"
     style="background-color: #A34A4A; border-radius: 15px;">

                        <h4 class="mb-3 fw-bold" style="font-size: 1.2rem;">Belanja di Sini</h4>
                        <p class="mb-4" style="font-size: 0.95rem;">
                            Dapatkan potongan harga khusus di store online kami!
                        </p>
                        <div class="d-flex justify-content-around flex-wrap gap-3 mt-3">
                            <div class="text-center">
                                <a href="{{ $social->shopee_link }}" target="_blank">
                                    <img src="{{ asset('images/shopee.png') }}" width="80" height="80" class="rounded-circle mb-2 shadow-sm img-fluid" alt="Shopee">
                                </a>
                                <p class="mb-0 fw-bold">Shopee</p>
                            </div>
                            <div class="text-center">
                                <a href="{{ $social->tokopedia_link }}" target="_blank">
                                    <img src="{{ asset('images/tokopedia.png') }}" width="80" height="80" class="rounded-circle mb-2 shadow-sm img-fluid" alt="Tokopedia">
                                </a>
                                <p class="mb-0 fw-bold">Tokopedia</p>
                            </div>
                            <div class="text-center">
                                <a href="{{ $social->lazada_link }}" target="_blank">
                                    <img src="{{ asset('images/lazada.png') }}" width="80" height="80" class="rounded-circle mb-2 shadow-sm img-fluid" alt="Lazada">
                                </a>
                                <p class="mb-0 fw-bold">Lazada</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @endif
    </div>
</section>





    @if($cta)
<section class="closing-cta py-5" style="background: linear-gradient(135deg, #A34A4A, #7A2D2D);">
    <div class="container">
        <div class="rounded-4 shadow-lg p-4 p-md-5 position-relative overflow-hidden"
             style="background: url('{{ asset('images/image2.png') }}') center/cover no-repeat;">

            <div class="row align-items-center text-center text-md-start" style="z-index:2; position: relative;">

                {{-- Text kiri --}}
                <div class="col-md-6 mb-4 mb-md-0">
                    <h2 class="fw-bold mb-4 display-6"
                        style="color:#222; line-height:1.3; font-size:clamp(1.5rem,4vw,2.5rem);">
                        {!! nl2br(e($cta->title)) !!}
                    </h2>
                    <a href="{{ route('front.catalog.index') }}"
                       class="btn btn-lg px-4 px-md-5 py-2 py-md-3 fw-semibold shadow-sm text-white"
                       style="background: linear-gradient(135deg,#9B4141,#6C1E1E); border:none; border-radius:12px;">
                        Dapatkan Sekarang
                    </a>
                </div>

                {{-- Gambar kanan --}}
                @if($cta->image)
                <div class="col-md-6">
                    <img src="{{ asset($cta->image) }}"
                         alt="Model Fashion"
                         class="img-fluid rounded-4"
                         style="max-height:380px; object-fit:cover; width:100%;">
                </div>
                @endif

            </div>
        </div>
    </div>
</section>
@endif


    <div class="modal fade" id="quickViewModal" tabindex="-1" aria-labelledby="quickViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0 position-relative">
                <button type="button" class="btn-close position-absolute" id="modalCloseButton"
                        style="right: 20px; top: 20px; z-index: 1050;"
                        data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 pt-0">
                <div class="row g-4 align-items-stretch">
                    {{-- Gambar Produk --}}
                    <div class="col-md-6 text-center d-flex">
                        <img id="modal-product-image"
                             src=""
                             alt="Product Image"
                             class="img-fluid rounded shadow-sm w-100 h-100"
                             style="object-fit: cover;">
                    </div>

                    {{-- Detail Produk --}}
                    <div class="col-md-6 d-flex flex-column h-100">
                        <div>
                            <h2 id="modal-product-name" class="fw-bold mb-2" style="color:#A34A4A;"></h2>
                            <h4 class="mt-2 mb-3">
                                <span id="modal-product-price" class="fw-bold" style="color:#A34A4A; font-size: 1.8rem;"></span>
                            </h4>

                            <div id="modal-product-description"
                                 class="text-muted small mb-2"
                                 style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; max-height: 4.5em; white-space: pre-line;">
                            </div>

                            <p class="fw-bold mb-4 text-muted" id="modal-product-stock"></p>

                            {{-- Varian --}}
                            <div id="modal-product-variants" class="mb-3"></div>
                        </div>

                        {{-- Tombol --}}
                        <div class="mt-auto">
                            <a id="view-product-detail"
                               href="#"
                               class="btn text-white w-100"
                               style="background-color: #A34A4A; border-radius: 30px; padding: 12px 24px;">
                                <i class="fas fa-shopping-cart me-2"></i> Lihat Detail
                            </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>



<!-- ============================= -->
<!-- CHATBOT WIDGET -->
<!-- ============================= -->
<!-- Widget Chatbot -->
<div id="chatbotWidget" class="card shadow"
     style="display:none; width:350px; position:fixed; bottom:120px; right:20px; z-index:1200; border-radius:12px;">

    <!-- Chat Section -->
    <div id="chatbotChat">
        <div class="card-header d-flex justify-content-between align-items-center py-3"
             style="background-color: #A34A4A; color: white; border-top-left-radius:12px; border-top-right-radius:12px;">
            <div class="d-flex align-items-center">
                <div class="me-2" style="width:30px; height:30px; background-color:white; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                    <i class="fas fa-robot" style="color:#A34A4A;"></i>
                </div>
                <h6 class="mb-0 fw-bold">Ahlinya Retail CS</h6>
            </div>
            <button type="button" class="btn-close btn-close-white" id="closeChatbot"></button>
        </div>
        <div class="card-body p-3" id="chatbot-body"
             style="height: 300px; overflow-y: auto; background-color: #f8f9fa;">
            <!-- Pesan chatbot muncul di sini -->
        </div>
        <div class="card-footer p-3">
            <div id="chatbot-input-container"></div>
            <div id="chatbot-options" class="d-flex flex-wrap gap-2 mt-2"></div>
        </div>
    </div>
</div>

<!-- Ikon WhatsApp -->
<a href="#" class="btn btn-success rounded-circle position-fixed bottom-0 end-0 m-4 shadow" id="openChatbot"
   style="width: 60px; height: 60px; font-size: 1.5rem; z-index: 1050; display: flex; align-items: center; justify-content: center;">
    <i class="fab fa-whatsapp"></i>
</a>

<meta name="csrf-token" content="{{ csrf_token() }}">

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const quickViewModal = document.getElementById('quickViewModal');
    const viewProductDetailBtn = document.getElementById('view-product-detail');

    quickViewModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const productId = button.getAttribute('data-product-id');

        // Reset modal content
        document.getElementById('modal-product-image').src = '';
        document.getElementById('modal-product-name').textContent = '';
        document.getElementById('modal-product-price').textContent = '';
        document.getElementById('modal-product-description').textContent = '';
        document.getElementById('modal-product-stock').textContent = 'Stok: Tersedia';
        document.getElementById('modal-product-variants').innerHTML = '';

        // Set URL untuk tombol detail produk
        viewProductDetailBtn.href = `/catalog/${productId}`;

        fetch(`/product/${productId}/details`)
            .then(response => response.json())
            .then(data => {
                // Update modal content with fetched data
                // Hanya set gambar jika tersedia
if (data.images && data.images.length > 0) {
    document.getElementById('modal-product-image').src = `{{ asset('') }}${data.images[0].image_path}`;
}
                document.getElementById('modal-product-name').textContent = data.name;
                document.getElementById('modal-product-price').textContent = `Rp. ${data.selling_price.toLocaleString('id-ID')}`;
                // Konversi tag <p> menjadi baris baru dan hapus tag lainnya
const formattedText = data.description
    .replace(/<p>/g, '')      // Hapus tag <p> pembuka
    .replace(/<\/p>/g, '\n')  // Ganti tag </p> penutup dengan baris baru
    .replace(/<[^>]*>/g, ''); // Hapus tag HTML lainnya

document.getElementById('modal-product-description').textContent = formattedText;
                document.getElementById('modal-product-stock').textContent = 'Stok: Tersedia';

                // Generate variant options
                const variantsContainer = document.getElementById('modal-product-variants');
                variantsContainer.innerHTML = ''; // Kosongkan kontainer varian

                if (data.variants && data.variants.length > 0) {
                    const colors = [...new Set(data.variants.map(v => v.color))];
                    const sizes = [...new Set(data.variants.map(v => v.size))];

                    // Color selection
                    if (colors.length > 0) {
                        let colorHtml = '<h6 class="fw-bold mt-2">Warna</h6><div class="d-flex flex-wrap gap-2">';
                        colors.forEach(color => {
                            colorHtml += `<button type="button" class="btn btn-outline-dark btn-sm rounded-pill">${color}</button>`;
                        });
                        colorHtml += '</div>';
                        variantsContainer.innerHTML += colorHtml;
                    }

                    // Size selection
                    if (sizes.length > 0) {
                        let sizeHtml = '<h6 class="fw-bold mt-2">Ukuran</h6><div class="d-flex flex-wrap gap-2">';
                        sizes.forEach(size => {
                            sizeHtml += `<button type="button" class="btn btn-outline-dark btn-sm rounded-pill">${size}</button>`;
                        });
                        sizeHtml += '</div>';
                        variantsContainer.innerHTML += sizeHtml;
                    }
                }
            })
            .catch(error => console.error('Error fetching product details:', error));
    });

    // Flash Sale Timer Logic
    const timerElement = document.getElementById('flash-sale-timer');
    let totalSeconds = 3600;

    function formatTime(sec) {
        const hours = Math.floor(sec / 3600);
        const minutes = Math.floor((sec % 3600) / 60);
        const seconds = sec % 60;
        return [
            String(hours).padStart(2, '0'),
            String(minutes).padStart(2, '0'),
            String(seconds).padStart(2, '0')
        ].join(':');
    }

    function updateTimer() {
        if (totalSeconds < 0) {
            totalSeconds = 3600;
        }
        timerElement.textContent = formatTime(totalSeconds);
        totalSeconds--;
    }
    setInterval(updateTimer, 1000);


        // =============================
        const chatbotWidget = document.getElementById('chatbotWidget');
        const openChatbotBtn = document.getElementById('openChatbot');
        const closeChatbotBtn = document.getElementById('closeChatbot');
        const chatbotBody = document.getElementById('chatbot-body');
        const chatbotInputContainer = document.getElementById('chatbot-input-container');
        const chatbotOptionsContainer = document.getElementById('chatbot-options');

        // Buka chatbot
        openChatbotBtn.addEventListener('click', function(e) {
            e.preventDefault();
            chatbotWidget.style.display = 'block';
            startChatbot();
        });

        // Tutup chatbot
        closeChatbotBtn.addEventListener('click', function() {
            chatbotWidget.style.display = 'none';
        });

        // Fungsi mulai chat
        function startChatbot() {
            chatbotBody.innerHTML = '';
            chatbotInputContainer.innerHTML = '';
            chatbotOptionsContainer.innerHTML = '';

            fetch("{{ route('chatbot.show') }}", {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(response => response.json())
            .then(data => {
                renderBotMessage(data.reply);
                renderNameForm();
            })
            .catch(error => console.error('Error starting chatbot:', error));
        }

        function renderNameForm() {
            chatbotInputContainer.innerHTML = '';
            chatbotOptionsContainer.innerHTML = '';
            chatbotInputContainer.style.display = 'block';

            chatbotInputContainer.innerHTML = `
                <form id="chatbotNameForm">
                    <div class="input-group">
                        <input type="text" class="form-control" id="chatbot-name-input"
                               placeholder="Masukkan nama lengkap anda" aria-label="Masukkan nama lengkap anda"
                               style="border: 1px solid #A34A4A; border-radius: 6px 0 0 6px;">
                        <button class="btn" type="submit"
                                style="background-color: #A34A4A; color: white; border-radius: 0 6px 6px 0;">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            `;

            document.getElementById('chatbotNameForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const nameInput = document.getElementById('chatbot-name-input').value.trim();
                if (nameInput) {
                    renderUserMessage(nameInput);
                    sendMessage(nameInput);
                }
            });
        }

        function renderChatForm() {
            chatbotInputContainer.innerHTML = '';
            chatbotInputContainer.style.display = 'block';
            chatbotOptionsContainer.style.display = 'none';

            chatbotInputContainer.innerHTML = `
                <form id="chatbotForm">
                    <div class="input-group">
                        <input type="text" class="form-control" id="chatbot-input"
                               placeholder="Ketik pesan..." aria-label="Ketik pesan..."
                               style="border: 1px solid #A34A4A; border-radius: 6px 0 0 6px;">
                        <button class="btn" type="submit" id="chatbot-send-btn"
                                style="background-color: #A34A4A; color: white; border-radius: 0 6px 6px 0;">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            `;

            document.getElementById('chatbotForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const userInput = document.getElementById('chatbot-input').value.trim();
                if (userInput) {
                    renderUserMessage(userInput);
                    sendMessage(userInput);
                }
            });
        }

        function renderBotMessage(message) {
            const botMessageHtml = `
                <div style="display:flex; margin-bottom:12px;">
                    <div style="width:30px; height:30px; background-color:#A34A4A; border-radius:50%; display:flex; align-items:center; justify-content:center; margin-right:8px; flex-shrink:0;">
                        <i class="fas fa-robot text-white" style="font-size:14px;"></i>
                    </div>
                    <div style="
                        background-color:#f1f0f0;
                        color:#000;
                        padding:10px 14px;
                        border-radius:12px;
                        max-width:80%;
                        font-size:14px;
                        ">
                        ${message.replace(/\n/g, '<br>')}
                    </div>
                </div>
            `;
            chatbotBody.innerHTML += botMessageHtml;
            chatbotBody.scrollTop = chatbotBody.scrollHeight;
        }

        function renderUserMessage(message) {
            const userMessageHtml = `
                <div style="display:flex; justify-content:flex-end; margin-bottom:12px;">
                    <div style="
                        background-color:#A34A4A;
                        color:#fff;
                        padding:10px 14px;
                        border-radius:12px;
                        max-width:80%;
                        font-size:14px;
                        ">
                        ${message}
                    </div>
                    <div style="width:30px; height:30px; background-color:#A34A4A; border-radius:50%; display:flex; align-items:center; justify-content:center; margin-left:8px; flex-shrink:0;">
                        <i class="fas fa-user text-white" style="font-size:14px;"></i>
                    </div>
                </div>
            `;
            chatbotBody.innerHTML += userMessageHtml;
            chatbotBody.scrollTop = chatbotBody.scrollHeight;
        }

        function renderOptions(options) {
            let optionsHtml = '';
            options.forEach(option => {
                const value = option.id || option;
                const name = option.name || option;
                const url = option.url || '';

                const dataAttribute = url ? `data-url="${url}"` : '';
                const btnClass = value === 'whatsapp_link' ? 'btn-success' : 'btn-outline-primary';

                optionsHtml += `
                    <button class="btn ${btnClass} chatbot-option-btn"
                            data-value="${value}" ${dataAttribute}
                            style="font-size:12px; padding:6px 12px; border-radius:6px;">
                        ${name}
                    </button>`;
            });
            chatbotOptionsContainer.innerHTML = optionsHtml;
            chatbotOptionsContainer.style.display = 'flex';

            document.querySelectorAll('.chatbot-option-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const url = this.dataset.url;
                    if (url) {
                        window.open(url, '_blank');
                    } else {
                        const value = this.dataset.value;
                        const name = this.textContent.trim();
                        renderUserMessage(name);
                        sendMessage(value);
                    }
                });
            });
        }

        function sendMessage(input) {
            chatbotInputContainer.style.display = 'none';
            chatbotOptionsContainer.style.display = 'none';

            fetch("{{ route('chatbot.handle') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ input: input })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                renderBotMessage(data.reply);
                chatbotOptionsContainer.innerHTML = '';
                if (data.options && data.options.length > 0) {
                    renderOptions(data.options);
                } else {
                    renderChatForm();
                }
            })
            .catch(error => {
                console.error('Error handling message:', error);
                renderBotMessage('Maaf, terjadi kesalahan. Silakan coba lagi.');
                renderChatForm();
            });
        }
    });
</script>
@endpush
