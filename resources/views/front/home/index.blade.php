@extends('layouts.common.app')

@section('content')
<main>
    {{-- Hero Section --}}
    {{-- Hero Section --}}
<section class="hero-section text-md-start py-5"
         style="background: linear-gradient(135deg, #ffffff 0%, #f8f8f8 100%);
                 position: relative; overflow: hidden;">
    {{-- Wave background dekoratif --}}
    <div style="position: absolute; top:0; left:0; width:100%; height:100%;
                 background: url('{{ asset('images/image2.png') }}') no-repeat center center / cover;
                 opacity:0.3; z-index:0;">
    </div>

    <div class="container d-flex align-items-center h-100 position-relative" style="z-index:1;">
        <div class="row align-items-center">
            {{-- Gambar di kiri --}}
            <div class="col-md-6 mb-4 mb-md-0">
                <img src ="{{ asset('images/image1.png') }}" alt="Hero" class="img-fluid">
            </div>
            {{-- Teks di kanan --}}
            <div class="col-md-6">
                <h1 class="display-4 fw-bold mb-4" style="font-family: 'Playfair Display', serif; line-height:1.3;">
                    Belanja Mudah<br>
                    Harga <span style="color: #CC3333;">Bersahabat,</span><br>
                    <span style="color: #CC3333;">Kualitas</span> Terjamin
                </h1>
                <p class="lead" style="color: #555;">
                    Temukan produk terbaik dengan harga yang tidak akan menguras kantong Anda.
                </p>
                <a href="{{ route('customer.auth.registers.index') }}"
                   class="btn btn-danger btn-lg mt-4"
                   style="background-color: #9B4141; border-radius: 8px; padding: 10px 30px;">
                    Register
                </a>
            </div>
        </div>
    </div>
</section>




    {{-- Highlight Keunggulan --}}
    <section class="py-5" style="background:#f8f9fa;"> {{-- background abu-abu terang --}}
    <div class="container text-center">
        {{-- Judul --}}
        <h2 class="fw-bold mb-5" style="color:#9B4141;">Mengapa Memilih Kami?</h2>

        <div class="row justify-content-center">
            {{-- Produk Original --}}
            <div class="col-md-3 mb-4">
                <i class="fas fa-certificate fa-3x"  style="color: #9B4141;"></i>
                <h5 class="color: #9B4141;">Produk Original</h5>
                <p class="text-muted medium">Kami menjamin produk yang selalu baru dan berkualitas.</p>
            </div>

            {{-- Harga Kompetitif --}}
            <div class="col-md-3 mb-4">
                <i class="fas fa-tags fa-3x" style="color: #9B4141;"  ></i>
                <h5 style="color: #9B4141;" >Harga Kompetitif</h5>
                <p class="text-muted medium">Dapatkan harga terbaik di pasar tanpa mengorbankan kualitas.</p>
            </div>

            {{-- Garansi Uang Kembali --}}
            <div class="col-md-3 mb-4">
                <i class="fas fa-undo fa-3x" style="color: #9B4141;"></i>
                <h5 style="color: #9B4141;">Garansi Uang Kembali</h5>
                <p class="text-muted medium">Jika tidak puas, uang Anda akan kami kembalikan.</p>
            </div>

            {{-- Pengiriman Cepat --}}
            <div class="col-md-3 mb-4">
                <i class="fas fa-shipping-fast fa-3x" style="color: #9B4141;" ></i>
                <h5 style="color: #9B4141;">Pengiriman Cepat</h5>
                <p class="text-muted medium">Pesanan Anda akan sampai tepat waktu dan aman.</p>
            </div>
        </div>
    </div>
</section>




    {{-- Best Seller --}}
<section id="best-seller" class="best-seller-section py-5">
    <div class="container">
        <h2 class="text-center" style="color: #9B4141;">Best Seller</h2>
        <p class="text-center text-muted mb-5">The Most Popular Choices Right Now</p>
        <div class="row">
            @forelse($bestSellerProducts as $product)
            <div class="col-md-3 mb-4">
     <div class="card h-100 shadow-sm border-0 product-card">
         <div class="position-relative">
             {{-- Tambahkan div pembungkus dengan inline style untuk tinggi tetap --}}
             <div style="height: 250px; overflow: hidden;">
                 <img src="{{ asset($product->image) }}" class="card-img-top product-image w-100 h-100 object-fit-cover" alt="{{ $product->name }}">
             </div>

             {{-- Badge dan icon lainnya tetap di sini --}}
             @if($product->promo_label)
             <span class="position-absolute top-0 end-0 m-2 px-2 py-1 text-white fw-bold promo-badge"
                   style="background:#A34A4A; border-radius:6px; font-size:0.85rem;">
                 {{ $product->promo_label }}
             </span>
             @endif

         </div>

                     <div class="card-body text-left d-flex flex-column">
                         <h6 class="card-title fw-bold mb-1 product-name">{{ $product->name }}</h6>
                         {{-- Tambahkan deskripsi untuk kompatibilitas modal --}}
                         <p class="card-text text-muted small flex-grow-1 product-description">
                             {{ Str::limit(strip_tags($product->description), 60) }}
                         </p>
                         <p class="card-text fw-bold mb-2 product-price" style="color: #CC3333;">Rp. {{ number_format($product->selling_price, 0, ',', '.') }}</p>
                         {{-- Ganti tag <a> menjadi <button> dan tambahkan atribut modal --}}
                         <button class="btn btn-danger w-100 mt-auto quick-view-btn"
                                 data-bs-toggle="modal"
                                 data-bs-target="#quickViewModal"
                                 data-product-id="{{ $product->id }}">
                             Buy
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




    {{-- Flash Sale --}}
    {{-- Flash Sale --}}
{{-- Flash Sale --}}
<section id="flash-sale" class="flash-sale-section py-5" style="background:#fff;">
        <div class="container">
            <div class="row g-4 align-items-stretch">
                {{-- Kolom Kiri: Produk Flash Sale --}}
                <div class="col-lg-6 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold" style="color:#B22929; font-size:1.25rem;">FLASH SALE</h5>
                        {{-- Timer Flash Sale --}}
                        <span id="flash-sale-timer" class="px-3 py-1 text-white fw-bold rounded"
                              style="background:#B22929; font-size:0.85rem;">
                            00:00:00
                        </span>
                        <a href="{{ route('front.catalog.index') }}" class="fw-bold"
                           style="color:#B22929; font-size:0.9rem;">
                            Lihat Semua →
                        </a>
                    </div>
                    {{-- Kontainer untuk produk Flash Sale (2 per baris) --}}
                    <div class="row g-3 flex-grow-1">
                        @forelse($flashSaleProducts as $product)
                            <div class="col-6">
                                {{-- PERBAIKAN: Menambahkan kelas product-card --}}
                                <div class="card h-100 shadow-sm border-0 rounded-3 text-center product-card">
                                    {{-- Gambar Produk --}}
                                    <div class="position-relative" style="height:200px; overflow:hidden;">
                                        {{-- PERBAIKAN: Menambahkan kelas product-image --}}
                                        <img src="{{ asset(str_replace('\\', '/', $product->image)) }}"
                                             class="w-100 h-100 object-fit-cover product-image"
                                             alt="{{ $product->name }}"
                                             style="border-radius:12px;">
                                        {{-- Badge Flash Sale --}}
                                        <span class="position-absolute top-0 end-0 m-2 px-2 py-1 text-white fw-bold"
                                              style="background:#B22929; border-radius:6px; font-size:0.7rem;">
                                            FLASH SALE
                                        </span>
                                    </div>
                                    {{-- Nama & Harga --}}
                                    <div class="p-3 d-flex flex-column">
                                        {{-- PERBAIKAN: Menambahkan kelas product-name --}}
                                        <h6 class="fw-bold mb-1 product-name" style="font-size:0.95rem;">{{ $product->name }}</h6>
                                        {{-- PERBAIKAN: Menambahkan kelas product-description --}}
                                        <p class="mb-2 text-muted small flex-grow-1 product-description" style="font-size:0.8rem;">
                                            {{ Str::limit(strip_tags($product->description), 40) }}
                                        </p>
                                        {{-- PERBAIKAN: Menambahkan kelas product-price --}}
                                        <p class="fw-bold mb-3 product-price" style="color:#B22929; font-size:0.95rem;">
                                            Rp. {{ number_format($product->selling_price, 0, ',', '.') }}
                                        </p>
                                        {{-- Tombol Buy --}}
                                        <button class="btn w-100 text-white mt-auto quick-view-btn"
                                                style="background:#B22929; border-radius:8px; font-size:0.85rem;"
                                                data-bs-toggle="modal"
                                                data-bs-target="#quickViewModal"
                                                data-product-id="{{ $product->id }}">
                                            Buy
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-center col-12">Tidak ada produk flash sale saat ini.</p>
                        @endforelse
                    </div>
                </div>
                {{-- Kolom Kanan: Banner Dinamis --}}
                <div class="col-lg-6 d-flex">
                    @if($banner)
                        @php
                            $cleanedPath = str_replace('\\', '/', $banner->image);
                            $finalPath = Str::startsWith($cleanedPath, 'back_assets') ? $cleanedPath : 'storage/' . $cleanedPath;
                        @endphp
                        <a href="{{ $banner->link }}" target="_blank" class="w-100">
                            <div class="card border-0 shadow-sm h-100 d-flex align-items-center justify-content-center"
                                style="border-radius:1rem; overflow:hidden; background:#fff;">
                                <img src="{{ asset($finalPath) }}" alt="{{ $banner->title }}"
                                     class="img-fluid"
                                     style="max-height: 400px; width: 100%; object-fit: cover; border-radius:1rem;">
                            </div>
                        </a>
                    @else
                        <div class="card bg-light p-5 border-0 shadow-sm text-center w-100 h-100">
                            <h4 class="text-muted">Promo menarik segera hadir!</h4>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>







    {{-- Penilaian Produk --}}
    {{-- resources/views/product/reviews.blade.php --}}
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
                <img src="{{ $testimonial->customer_photo ? asset($testimonial->customer_photo) : 'https://via.placeholder.com/40' }}"
                     alt="{{ $testimonial->customer_name }}"
                     class="rounded-circle me-2"
                     style="width:40px; height:40px; object-fit:cover;">
                <div>
                    <h6 class="fw-bold mb-0">{{ $testimonial->customer_name }}</h6>
                    <small class="text-muted">{{ \Carbon\Carbon::parse($testimonial->created_at)->format('d-m-Y') }}</small>
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

            {{-- Isi Review --}}
            <p class="mb-2">
    {{ strip_tags($testimonial->review) }}
</p>

            {{-- Foto/Media (jika ada) --}}
            @if($testimonial->media)
            <div class="d-flex gap-2">
                {{-- Loop melalui media --}}
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
        {{-- Pagination --}}
<div class="d-flex justify-content-center mt-4">
    {{ $testimonials->links('vendor.pagination.bootstrap-5') }}
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

        <div class="row align-items-center">
            {{-- Foto Founder --}}
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm">
                    <img src="{{ asset('images/image4.png') }}" class="card-img-top" alt="Founder">
                    <div class="card-body">
                        <h6 class="fw-bold mb-1">Sarah Wijaya</h6>
                        <p class="text-muted mb-2">Founder & Creative Director</p>
                        <p class="mb-0">
                            Kami percaya bahwa desain yang baik tidak hanya tentang estetika,
                            tetapi tentang menciptakan pengalaman yang bermakna bagi setiap pengguna.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Foto Tim --}}
            <div class="col-md-8 mb-3">
                <img src="{{ asset('images/image3.png') }}" class="img-fluid rounded shadow" alt="Tim Kami">
            </div>
        </div>
    </div>
</section>




    <section class="py-5">
    <div class="container">
        <h2 class="text-center fw-bold mb-5" style="color:#9B4141;">
    Perjalanan Kami
</h2>

        <div class="position-relative">
            <div class="position-absolute top-0 bottom-0 start-0 ms-2 border-start border-2 border-secondary"></div>

            <div class="d-flex mb-4">
                <div class="me-3 mt-2">
                    <span class="d-inline-block rounded-circle bg-danger" style="width:12px;height:12px;"></span>
                </div>
                <div class="card border border-danger rounded-3 shadow-sm w-100">
                    <div class="card-body">
                        <h6 class="fw-bold" style="color: #9B4141;">2019 Awal Mula</h6>
                        <p class="mb-0 small">
                            Dimulai dari sebuah kafe kecil di Jakarta, Sarah dan dua rekannya memutuskan untuk
                            mengubah passion mereka terhadap desain menjadi sebuah bisnis yang dapat membantu UMKM lokal.
                        </p>
                    </div>
                </div>
            </div>

            <div class="d-flex mb-4">
                <div class="me-3 mt-2">
                    <span class="d-inline-block rounded-circle bg-danger" style="width:12px;height:12px;"></span>
                </div>
                <div class="card border border-danger rounded-3 shadow-sm w-100">
                    <div class="card-body">
                        <h6 class="fw-bold" style="color: #9B4141;">2020 Momentum Pertama</h6>
                        <p class="mb-0 small">
                            Pandemi justru menjadi berkah tersembunyi. Banyak bisnis yang membutuhkan transformasi digital,
                            dan kami siap membantu dengan solusi desain yang terjangkau namun berkualitas.
                        </p>
                    </div>
                </div>
            </div>

            <div class="d-flex mb-4">
                <div class="me-3 mt-2">
                    <span class="d-inline-block rounded-circle bg-danger" style="width:12px;height:12px;"></span>
                </div>
                <div class="card border border-danger rounded-3 shadow-sm w-100">
                    <div class="card-body">
                        <h6 class="fw-bold" style="color: #9B4141;">2023 Awal Kemajuan</h6>
                        <p class="mb-0 small">
                            Tim berkembang menjadi 15 orang talenta terbaik dari berbagai disiplin ilmu. Kami pindah ke studio
                            yang lebih besar dan mulai mengerjakan proyek-proyek enterprise.
                        </p>
                    </div>
                </div>
            </div>

            <div class="d-flex">
                <div class="me-3 mt-2">
                    <span class="d-inline-block rounded-circle bg-danger" style="width:12px;height:12px;"></span>
                </div>
                <div class="card border border-danger rounded-3 shadow-sm w-100">
                    <div class="card-body">
                        <h6 class="fw-bold" style="color: #9B4141;">2025 Bertahan</h6>
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
        <h2 class="mb-2 fw-bold" style="color: #9B4141;">Keamanan dan Jaminan</h2>
        <p class="mb-5" style="color: #666;">
            Kami memberikan perlindungan dan jaminan penuh untuk setiap transaksi Anda
        </p>

        <div class="row g-4">
            {{-- Keamanan Transaksi --}}
            <div class="col-md-6">
                <div class="card p-4 h-100 shadow-sm border-0">
                    <div class="d-flex justify-content-center align-items-center mb-3">
                        <i class="fas fa-shield-alt fa-3x" style="color: #9B4141;"></i>
                    </div>
                    <h5 class="fw-bold" style="color:#9B4141;">Keamanan Transaksi</h5>
                    <ul class="list-unstyled text-start mt-3">
                         <li><i class="fas fa-check-circle me-2" style="color: #8B4513;"></i> Sistem pembayaran terenkripsi dengan teknologi SSL 256-bit.</li>
                        <li><i class="fas fa-check-circle me-2" style="color: #8B4513;"></i> Data pribadi Anda 100% tidak dibagikan ke pihak ketiga.</li>
                        <li><i class="fas fa-check-circle me-2" style="color: #8B4513;"></i> Transaksi diawasi oleh sistem keamanan real-time.</li>
                    </ul>
                </div>
            </div>

            {{-- Garansi Produk --}}
            <div class="col-md-6">
                <div class="card p-4 h-100 shadow-sm border-0">
                    <div class="d-flex justify-content-center align-items-center mb-3">
                        <i class="fas fa-certificate fa-3x" style="color: #9B4141;"></i>
                    </div>
                    <h5 class="fw-bold" style="color:#9B4141;">Garansi Produk</h5>
                    <ul class="list-unstyled text-start mt-3">
                         <li><i class="fas fa-check-circle me-2" style="color: #8B4513;"></i> Sistem pembayaran terenkripsi dengan teknologi SSL 256-bit.</li>
                        <li><i class="fas fa-check-circle me-2" style="color: #8B4513;"></i> Data pribadi Anda 100% tidak dibagikan ke pihak ketiga.</li>
                        <li><i class="fas fa-check-circle me-2" style="color: #8B4513;"></i> Transaksi diawasi oleh sistem keamanan real-time.</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Pembayaran --}}
        <div class="pembayaran-section mt-5">
            <h5 class="fw-bold mb-4" style="color:#7a2c2c;">Menerima Pembayaran :</h5>
            <div class="d-flex justify-content-center align-items-center flex-wrap">
                <div class="mx-4 text-center">
                    <i class="fas fa-qrcode fa-3x" style="color: #9B4141;"></i>
                    <p class="fw-bold mt-2">QRIS</p>
                </div>
                <div class="mx-4 text-center">
                    <i class="fas fa-university fa-3x" style="color: #9B4141;"></i>
                    <p class="fw-bold mt-2">Transfer BANK</p>
                </div>
                <div class="mx-4 text-center">
                    <i class="fas fa-wallet fa-3x" style="color: #9B4141;"></i>
                    <p class="fw-bold mt-2">E-Wallet</p>
                </div>
            </div>
        </div>
    </div>
</section>




    <section class="closing-cta py-5" style="background-color: #A34A4A;">
    <div class="container">
        <div class="bg-white rounded-4 shadow p-5 position-relative overflow-hidden">

            {{-- Background wave --}}
            <img src="{{ asset('images/image2.png') }}"
                 alt="Background Wave"
                 class="position-absolute top-0 start-0 w-100 h-100"
                 style="object-fit: cover; z-index:0; opacity:0.9;">

            <div class="row align-items-center position-relative" style="z-index:1;">

                {{-- Text di kiri --}}
                <div class="col-md-6 text-md-start text-center mb-4 mb-md-0">
                    <h2 class="fw-bold mb-4" style="color:#000;">
                        "Sudah siap tampil beda? <br>Temukan outfit terbaikmu di sini."
                    </h2>
                    <a href="{{ route('front.catalog.index') }}"
                       class="btn btn-danger btn-lg px-4"
                       style="background-color:#9B4141; border:none;">
                        Dapatkan Sekarang
                    </a>
                </div>

                {{-- Gambar model di kanan --}}
                <div class="col-md-6 text-center">
                    <img src="{{ asset('images/image6.png') }}"
                             alt="Model Fashion"
                             class="img-fluid"
                             style="max-height:380px; object-fit:cover;">
                </div>

            </div>
        </div>
    </div>
</section>
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

                        {{-- Form baru untuk menambahkan ke keranjang --}}
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

</main>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const quickViewModal = document.getElementById('quickViewModal');

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

            // Set action form ke route add cart
            const form = quickViewModal.querySelector('form');
            form.action = `/customer/carts/${productId}/add`;
        }
    });

    // Optional: Jika customer belum login, redirect ke login
    const addToCartForm = quickViewModal.querySelector('form');
    addToCartForm.addEventListener('submit', function (e) {
        @if(!auth()->guard('customer')->check())
            e.preventDefault();
            window.location.href = "{{ route('customer.auth.login.index') }}";
        @endif
    });

    // Flash Sale Timer Logic
    const timerElement = document.getElementById('flash-sale-timer');
    let totalSeconds = 3600; // 1 jam = 3600 detik

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
            totalSeconds = 3600; // Reset ke 1 jam
        }

        timerElement.textContent = formatTime(totalSeconds);
        totalSeconds--;
    }

    // Jalankan timer setiap detik
    setInterval(updateTimer, 1000);
});
</script>
@endpush
