@extends('layouts.common.app')

@section('content')
<div class="container my-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="row align-items-stretch">
        <div class="mb-4">
            <a href="{{ route('front.catalog.index') }}"
                class="d-inline-flex align-items-center text-decoration-none fw-bold"
                style="color:#A34A4A; font-size:1.25rem;">
                <i class="fas fa-arrow-left me-2" style="font-size:2.5rem;"></i> Back
            </a>
        </div>
        <div class="col-md-6 d-flex flex-column align-items-center">
            <div class="product-image-main mb-3 text-center"
                 style="width:100%; max-width:500px; height:500px; margin:0 auto; overflow:hidden; display:flex; align-items:center; justify-content:center; background:#f9f9f9;">
                <div id="main-media-container" style="width:100%; height:100%; display:flex; align-items:center; justify-content:center;">
                    @if($product->images && count($product->images) > 0)
                        @php
                            $firstMedia = $product->images->first();
                        @endphp
                        @if($firstMedia->is_video)
                            <video src="{{ asset($firstMedia->image_path) }}" controls class="img-fluid main-media" style="width:100%; height:100%; object-fit:cover; object-position:center;"></video>
                        @else
                            <img src="{{ asset($firstMedia->image_path) }}" alt="{{ $product->name }}" class="img-fluid main-media" style="width:100%; height:100%; object-fit:cover; object-position:center;">
                        @endif
                    @else
                        <img src="{{ asset('images/placeholder.jpg') }}" alt="{{ $product->name }}" class="img-fluid main-media" style="width:100%; height:100%; object-fit:contain; object-position:center;">
                    @endif
                </div>
            </div>
            @if($product->images && count($product->images) > 1)
                <div class="position-relative mb-5 mb-md-0">
                    <button type="button"
                            class="btn btn-light shadow-sm position-absolute top-50 start-0 translate-middle-y"
                            id="thumb-prev"
                            style="z-index: 2; border-radius: 50%; width: 36px; height: 36px; padding:0;">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <div class="product-thumbnails d-flex gap-2 flex-nowrap overflow-auto py-2 px-5"
                         style="scroll-behavior: smooth;">
                        @foreach($product->images as $index => $image)
                            @if($image->is_video)
                                <div class="img-thumbnail thumbnail-video d-flex align-items-center justify-content-center"
                                     style="width: 80px; height: 80px; object-fit: cover; cursor: pointer; border: 2px solid {{ $index === 0 ? '#A34A4A' : '#ddd' }}; background-color: #f0f0f0;"
                                     data-path="{{ asset($image->image_path) }}"
                                     data-index="{{ $index }}"
                                     data-is-video="true">
                                    <i class="fas fa-play" style="color: #A34A4A; font-size: 20px;"></i>
                                </div>
                            @else
                                <img src="{{ asset($image->image_path) }}"
                                     alt="Thumbnail {{ $index + 1 }}"
                                     class="img-thumbnail thumbnail-img"
                                     style="width: 80px; height: 80px; object-fit: cover; cursor: pointer; border: 2px solid {{ $index === 0 ? '#A34A4A' : '#ddd' }};"
                                     data-path="{{ asset($image->image_path) }}"
                                     data-index="{{ $index }}"
                                     data-is-video="false">
                            @endif
                        @endforeach
                    </div>
                    <button type="button"
                            class="btn btn-light shadow-sm position-absolute top-50 end-0 translate-middle-y"
                            id="thumb-next"
                            style="z-index: 2; border-radius: 50%; width: 36px; height: 36px; padding:0;">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            @endif
        </div>
        <div class="col-md-6 d-flex flex-column justify-content-between">
            <div>
                <h2 class="fw-bold mb-2" style="color:#A34A4A;">{{ $product->name }}</h2>
                <h4 class="fw-bold mb-4" style="color:#A34A4A; font-size:1.9rem;">
                    Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                </h4>
                <form id="addToCartForm" action="{{ route('customer.carts.add', $product) }}" method="POST">
                    @csrf
                    <div id="validation-messages" class="mb-3"></div>
                    @if($product->variants && $product->variants->pluck('size')->unique()->count() > 0)
                        @php $sizes = $product->variants->pluck('size')->unique(); @endphp
                        <div class="mb-3">
                            <h6 class="fw-bold">Ukuran:</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($sizes as $size)
                                    <button type="button"
                                            class="btn variant-btn"
                                            style="border:1px solid #A34A4A; color:#1E1E1E; border-radius:6px; background-color: white; color: #1E1E1E;"
                                            data-type="size"
                                            data-value="{{ $size }}">
                                        {{ $size }}
                                    </button>
                                @endforeach
                            </div>
                            <input type="hidden" name="size" id="selected-size">
                        </div>
                    @endif
                    @if($product->variants && $product->variants->pluck('color')->unique()->count() > 0)
                        @php $colors = $product->variants->pluck('color')->unique(); @endphp
                        <div class="mb-3">
                            <h6 class="fw-bold">Warna:</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($colors as $color)
                                    <button type="button"
                                            class="btn variant-btn"
                                            style="border:1px solid #A34A4A; color:#1E1E1E; border-radius:6px; background-color: white; color: #1E1E1E;"
                                            data-type="color"
                                            data-value="{{ $color }}">
                                        {{ $color }}
                                    </button>
                                @endforeach
                            </div>
                            <input type="hidden" name="color" id="selected-color">
                        </div>
                    @endif
                    <div class="mb-3">
                        <h6 class="fw-bold">Kuantitas:</h6>
                        <input type="number" name="quantity" class="form-control"
                               value="1" min="1"
                               style="max-width:120px; border:1px solid #A34A4A; border-radius:0;">
                        <small class="text-muted">Stok Tersedia</small>
                    </div>
                    <div class="d-flex gap-3 mt-3">
                        <a href="{{ $whatsappUrl }}"
                           class="btn fw-bold flex-fill"
                           style="border:1px solid #A34A4A; background:white; color:#A34A4A; border-radius:0; padding:12px;"
                           target="_blank">
                            Tanyakan Produk Ini
                        </a>
                        <button type="submit"
                                class="btn fw-bold flex-fill text-white"
                                style="background-color:#A34A4A; border-radius:0; padding:12px;">
                            Tambahkan Ke Keranjang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="mt-5">
    <ul class="nav nav-underline mb-3" id="productTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold"
                    id="detail-tab"
                    data-bs-toggle="tab" data-bs-target="#detail"
                    type="button" role="tab"
                    aria-controls="detail" aria-selected="true">
                Detail
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold"
                    id="size-tab"
                    data-bs-toggle="tab" data-bs-target="#size"
                    type="button" role="tab"
                    aria-controls="size" aria-selected="false">
                Size
            </button>
        </li>
    </ul>
    <div class="tab-content border p-3 rounded" id="productTabContent">
        {{-- TAB PANE UNTUK DETAIL PRODUK --}}
        <div class="tab-pane fade show active" id="detail" role="tabpanel" aria-labelledby="detail-tab">
            <div class="text-start"> {{-- Ganti ke text-start untuk rata kiri --}}
                <h5 class="fw-bold mb-3">Deskripsi Produk</h5>
                <div>
                    {{-- TAMPILKAN DESKRIPSI PRODUK --}}
                    {!! nl2br($product->description ?? 'Tidak ada deskripsi.') !!}
                </div>
            </div>
        </div>

        {{-- TAB PANE UNTUK DETAIL UKURAN --}}
        <div class="tab-pane fade" id="size" role="tabpanel" aria-labelledby="size-tab">
            <div class="text-start"> {{-- Ganti ke text-start untuk rata kiri --}}
                <h5 class="fw-bold mb-3">Detail Ukuran</h5>
                @if($product->size_details || $product->size_chart_image)
                    {{-- TAMPILKAN TEXT DETAIL UKURAN JIKA ADA --}}
                    @if($product->size_details)
                        <div class="mb-3">
                            {!! nl2br($product->size_details) !!}
                        </div>
                    @endif

                    {{-- TAMPILKAN GAMBAR SIZE CHART JIKA ADA --}}
                    @if($product->size_chart_image)
                        <div class="mb-3">
                            <img src="{{ asset($product->size_chart_image) }}"
                                alt="Size Chart" class="img-fluid rounded">
                            {{-- Ubah class atau style agar tidak rata tengah --}}
                        </div>
                    @endif
                @else
                    <p>Detail ukuran belum tersedia.</p>
                @endif
            </div>
        </div>
    </div>
</div>
</div>

<div id="chatbotWidget" class="card shadow"
     style="display:none; width:350px; position:fixed; bottom:120px; right:20px; z-index:1200; border-radius:12px;">

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
            </div>
        <div class="card-footer p-3">
            <div id="chatbot-input-container"></div>
            <div id="chatbot-options" class="d-flex flex-wrap gap-2 mt-2"></div>
        </div>
    </div>
</div>

<a href="#" class="btn btn-success rounded-circle position-fixed bottom-0 end-0 m-4 shadow" id="openChatbot"
   style="width: 60px; height: 60px; font-size: 1.5rem; z-index: 1050; display: flex; align-items: center; justify-content: center;">
    <i class="fab fa-whatsapp"></i>
</a>

<meta name="csrf-token" content="{{ csrf_token() }}">

@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mainMediaContainer = document.getElementById('main-media-container');
        const thumbnails = document.querySelectorAll('.thumbnail-img, .thumbnail-video');
        const btnPrev = document.getElementById('thumb-prev');
        const btnNext = document.getElementById('thumb-next');

        const variantButtons = document.querySelectorAll('.variant-btn');
        const mainImage = document.getElementById('main-product-image');


        const tabs = document.querySelectorAll('#productTab .nav-link');
        const addToCartForm = document.getElementById('addToCartForm');
        const validationMessages = document.getElementById('validation-messages');

        // =============================
        // Validasi dan Submit Form
        // =============================
        if (addToCartForm) {
            addToCartForm.addEventListener('submit', function(e) {
                const selectedSize = document.getElementById('selected-size')?.value;
                const selectedColor = document.getElementById('selected-color')?.value;
                const hasSizes = document.querySelectorAll('.variant-btn[data-type="size"]').length > 0;
                const hasColors = document.querySelectorAll('.variant-btn[data-type="color"]').length > 0;

                if ((hasSizes && !selectedSize) || (hasColors && !selectedColor)) {
                    e.preventDefault();
                    alert('Silakan pilih warna dan ukuran produk.');
                }
            });
        }

        // =============================
        // Variant Button (warna & size)
        // =============================
        variantButtons.forEach(button => {
            button.addEventListener('click', function() {
                const type = this.dataset.type;
                const value = this.dataset.value;
                // Reset all buttons of the same type
                document.querySelectorAll(`.variant-btn[data-type="${type}"]`).forEach(btn => {
                    btn.style.backgroundColor = 'white';
                    btn.style.color = '#1E1E1E';
                });
                // Set the style for the clicked button
                this.style.backgroundColor = '#A34A4A';
                this.style.color = 'white';
                document.getElementById(`selected-${type}`).value = value;
            });
        });

        // =============================
        // Gallery Thumbnail
        // =============================
        if (thumbnails.length > 0 && mainMediaContainer) {
            let currentIndex = 0;

            function showMedia(index) {
                if (index < 0) index = thumbnails.length - 1;
                if (index >= thumbnails.length) index = 0;
                currentIndex = index;

                const mediaItem = thumbnails[currentIndex];
                const mediaPath = mediaItem.dataset.path;
                const isVideo = mediaItem.dataset.isVideo === 'true';

                // Clear existing media
                mainMediaContainer.innerHTML = '';

                if (isVideo) {
                    mainMediaContainer.innerHTML = `<video src="${mediaPath}" controls autoplay class="img-fluid main-media" style="width:100%; height:100%; object-fit:cover; object-position:center;"></video>`;
                } else {
                    mainMediaContainer.innerHTML = `<img src="${mediaPath}" alt="Product Image" class="img-fluid main-media" style="width:100%; height:100%; object-fit:cover; object-position:center;">`;
                }

                // Update active thumbnail border
                thumbnails.forEach(t => t.style.border = '2px solid #ddd');
                thumbnails[currentIndex].style.border = '2px solid #A34A4A';
            }

            thumbnails.forEach((thumb, idx) => {
                thumb.addEventListener('click', function() {
                    showMedia(idx);
                });
            });

            if (btnPrev) btnPrev.addEventListener('click', () => showMedia(currentIndex - 1));
            if (btnNext) btnNext.addEventListener('click', () => showMedia(currentIndex + 1));
        }

        // =============================
        // Tab Nav (Aktif merah, non-aktif hitam)
        // =============================
        function updateTabColors() {
            tabs.forEach(tab => {
                if (tab.classList.contains('active')) {
                    tab.style.setProperty('color', '#A34A4A', 'important');
                    tab.style.setProperty('border-color', '#A34A4A', 'important');
                } else {
                    tab.style.setProperty('color', '#000', 'important');
                    tab.style.setProperty('border-color', 'transparent', 'important');
                }
            });
        }
        updateTabColors();
        tabs.forEach(tab => {
            tab.addEventListener('shown.bs.tab', function () {
                updateTabColors();
            });
        });

        // =============================
        // CHATBOT LOGIC
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
