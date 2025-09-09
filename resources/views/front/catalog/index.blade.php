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
           value="{{ request('search') }}" style="border-radius: 20px 0 0 20px;">
    <button type="submit" class="btn bg-white border border-start-0" style="border-radius: 0 20px 20px 0;">
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


                    {{-- Gambar Produk --}}
                    <div style="height:250px; overflow:hidden;">
    @if($product->images && count($product->images) > 0)
        <img src="{{ asset($product->images[0]->image_path) }}"
             alt="{{ $product->name }}"
             class="w-100 h-100 product-image"
             style="object-fit:cover;">
    @endif
</div>

                    {{-- Body Card --}}
                    <div class="card-body d-flex flex-column text-center" style="background-color:#FDF7F7;">
                        <h6 class="fw-bold product-name">{{ $product->name }}</h6>

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

                    {{-- Icon Love & Cart di kanan atas tapi agak turun --}}


                    {{-- Gambar Produk --}}
                    <div style="height:250px; overflow:hidden;">
    @if($product->images && count($product->images) > 0)
        <img src="{{ asset($product->images[0]->image_path) }}"
             alt="{{ $product->name }}"
             class="w-100 h-100 product-image"
             style="object-fit:cover;">
    @endif
</div>

                    {{-- Body Card --}}
                    <div class="card-body d-flex flex-column text-center" style="background-color:#FDF7F7;">
                        <h6 class="fw-bold product-name">{{ $product->name }}</h6>

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
            <div class="modal-header border-0 pb-0 position-relative">
                <button type="button" class="btn-close position-absolute" id="modalCloseButton"
                        style="right: 20px; top: 20px; z-index: 1050;"
                        data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 pt-0">
                <div class="row g-4 align-items-stretch">
                    {{-- Gambar --}}
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
                            <!-- Sebelumnya -->


<!-- Jadi -->
<div id="modal-product-description"
     class="text-muted small mb-2"
     style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; max-height: 4.5em; white-space: pre-line;">
</div>

                            <p class="fw-bold mb-4 text-muted" id="modal-product-stock"></p>

                            {{-- Pilihan Varian --}}
                            <div id="modal-product-variants" class="mb-3"></div>
                        </div>

                        {{-- Tombol Beli --}}
                        <div class="mt-auto">
                            <a id="view-product-detail"
                               href="#"
                               class="btn text-white w-100"
                               style="background-color: #A34A4A; border-radius: 30px; padding: 12px 24px;">
                                <i class="fas fa-shopping-cart me-2"></i> Beli Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- TAMBAHKAN KODE CHATBOT DI SINI -->
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    const quickViewModal = document.getElementById('quickViewModal');
    const viewProductDetailBtn = document.getElementById('view-product-detail');
    const modalCloseButton = document.getElementById('modalCloseButton');

    // Fallback untuk tombol close modal
    function closeModal() {
        const modal = bootstrap.Modal.getInstance(quickViewModal);
        if (modal) {
            modal.hide();
        } else {
            // Jika instance tidak ditemukan, buat instance baru dan sembunyikan
            new bootstrap.Modal(quickViewModal).hide();
        }
    }

    // Tambahkan event listener untuk tombol close
    if (modalCloseButton) {
        modalCloseButton.addEventListener('click', closeModal);
    }

    // Ketika modal dibuka
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

        // Fetch product details from server
        fetch(`/product/${productId}/details`)
            .then(response => response.json())
            .then(data => {
                // PERBAIKAN: Hapus placeholder, hanya tampilkan gambar jika ada
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

        // =============================
    // CHATBOT LOGIC
    // =============================

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
