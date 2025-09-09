@extends('layouts.common.app')

@section('content')

<div class="container mt-4 mb-5">
    <h5 class="fw-bold mb-4">Keranjang Belanja</h5> <!-- Tambah margin bottom -->


    @if($cartItems->isNotEmpty())
    <form id="checkoutForm" action="{{ route('customer.checkout.prepare') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="table-responsive overflow-auto">
                    <table class="table align-middle" style="min-width: 650px;">
                        <thead class="border-bottom" style="border-color: #A34A4A !important;">
                            <tr>
                                <th class="align-middle ps-3" width="5%">
    <input type="checkbox" id="selectAll">
</th>

                                <th scope="col" class="ps-3">Produk</th> <!-- Tambah padding kiri -->
                                <th scope="col" class="text-end pe-3">Harga</th> <!-- Tambah padding kanan -->
                                <th scope="col" class="text-center">Kuantitas</th>
                                <th scope="col" class="text-end pe-3">Total</th> <!-- Tambah padding kanan -->
                                <th scope="col" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $item)
                                @php
                                    $itemPrice = $item->price_snapshot ?? ($item->selling_price ?? 0);
                                    $itemTotal = $itemPrice * $item->quantity;

                                    $imagePath = null;
                                    if ($item->product && $item->product->images && count($item->product->images) > 0) {
                                        $imagePath = asset($item->product->images[0]->image_path);
                                    } else if (isset($item->image) && !empty($item->image)) {
                                        $imagePath = asset($item->image);
                                    } else if ($item->product && isset($item->product->image) && !empty($item->product->image)) {
                                        $imagePath = asset($item->product->image);
                                    } else {
                                        $imagePath = asset('images/no-image.png');
                                    }
                                @endphp
                                <tr id="item-row-{{ $item->id }}" class="border-bottom" style="border-color: #A34A4A !important;">
                                    <td class="align-middle ps-3"> <!-- Tambah padding kiri dan align middle -->
                                        <input type="checkbox"
                                               name="cart_ids[]"
                                               value="{{ $item->id }}"
                                               class="cart-checkbox"
                                               data-price="{{ $itemTotal }}"
                                               data-original-price="{{ $itemPrice }}">
                                    </td>
                                    <td class="align-middle"> <!-- Ubah ke align middle -->
                                        <div class="d-flex align-items-center"> <!-- Ubah ke align items center -->
                                            <img src="{{ $imagePath }}"
                                                 alt="{{ $item->product->name ?? $item->product_name ?? '' }}"
                                                 class="img-fluid rounded me-3"
                                                 style="width:80px; height:80px; object-fit:cover;"
                                                 onerror="this.src='{{ asset('images/no-image.png') }}'">
                                            <div>
                                                <h6 class="mb-2 fw-bold">{{ $item->product->name ?? $item->product_name ?? '' }}</h6> <!-- Tambah font weight bold dan margin bottom -->
                                                <div class="mb-1"><small>Ukuran: {{ $item->variant->size ?? $item->size ?? '-' }}</small></div> <!-- Tambah margin bottom -->
                                                <div><small>Warna: {{ $item->variant->color ?? $item->color ?? '-' }}</small></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-end pe-3"> <!-- Tambah padding kanan dan align middle -->
                                        <div class="fw-medium">Rp {{ number_format($itemPrice, 0, ',', '.') }}</div> <!-- Tambah font weight -->
                                    </td>
                                    <td class="align-middle text-center"> <!-- Ubah ke align middle -->
                                        <div class="input-group input-group-sm quantity-control mx-auto" style="width:120px;">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="decrease" data-item-id="{{ $item->id }}"><i class="fas fa-minus"></i></button>
                                            <input type="number"
                                                   name="quantities[{{ $item->id }}]"
                                                   value="{{ $item->quantity }}"
                                                   min="1"
                                                   class="form-control text-center quantity-input"
                                                   data-item-id="{{ $item->id }}">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="increase" data-item-id="{{ $item->id }}"><i class="fas fa-plus"></i></button>
                                        </div>
                                    </td>
                                    <td class="fw-bold align-middle text-end pe-3" id="total-{{ $item->id }}"> <!-- Tambah padding kanan dan align middle -->
                                        Rp {{ number_format($itemTotal, 0, ',', '.') }}
                                    </td>
                                    <td class="align-middle text-center"> <!-- Ubah ke align middle -->
                                        <button type="button" class="btn btn-sm p-0" onclick="confirmDelete('{{ $item->id }}')" style="color: #A34A4A;">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-top" style="border-color: #A34A4A !important;">
                                <td colspan="4" class="text-end fw-bold py-3 pe-3">Total untuk Produk Dipilih</td> <!-- Tambah padding kanan -->
                                <td colspan="2" id="selected-total" class="fw-bold text-end py-3 pe-3"><strong>Rp 0</strong></td> <!-- Tambah padding kanan -->
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="col-12 col-lg-4 mt-4 mt-lg-0">
    <div class="border p-4 rounded bg-light" style="position: sticky; top: 20px;">
        <h5 class="mb-3">
            <strong>Subtotal (Produk Dipilih)</strong>
        </h5>
        <h4 class="fw-bold mb-3">
            Rp <span id="cart-subtotal">0</span>
        </h4>
        <p class="text-muted small mb-3">Pajak dan biaya pengiriman akan dihitung di halaman selanjutnya.</p>
        <div class="d-grid gap-2">
            <button type="submit" class="btn w-100" id="checkoutBtn" style="background-color: #A34A4A; color: white; border: none;">
                <i class="fas fa-shopping-cart me-2"></i> Pesan Sekarang
            </button>

            <a href="{{ route('front.catalog.index') }}" class="btn btn-outline-dark w-100">
                Belanja Lagi
            </a>
        </div>
    </div>
</div>
        </div>
    </form>
    @foreach($cartItems as $item)
        <form action="{{ route('customer.carts.remove', $item->id) }}" method="POST" id="delete-form-{{ $item->id }}" style="display:none;">
            @csrf
            @method('DELETE')
        </form>
    @endforeach
    @else
        <div class="card p-5 text-center">
        <h4 class="text-muted mb-3">Keranjang belanja Anda kosong.</h4>
        <a href="{{ route('front.catalog.index') }}" class="btn" style="background-color: #A34A4A; color: white;">
            Mulai belanja sekarang!
        </a>
    </div>
    @endif
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


@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.cart-checkbox');
        const checkoutForm = document.getElementById('checkoutForm');
        const checkoutBtn = document.getElementById('checkoutBtn');

        function formatRupiah(amount) {
            return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function updateItemTotal(itemId, quantity) {
            const checkbox = document.querySelector(`.cart-checkbox[value="${itemId}"]`);
            if (checkbox) {
                const pricePerUnit = parseFloat(checkbox.dataset.originalPrice);
                const total = pricePerUnit * quantity;
                checkbox.dataset.price = total;
                document.getElementById(`total-${itemId}`).innerText = `Rp ${formatRupiah(total)}`;
                updateSelectedTotal();
            }
        }

        function updateSelectedTotal() {
            let subtotal = 0;
            checkboxes.forEach(cb => {
                if (cb.checked) {
                    subtotal += parseInt(cb.dataset.price) || 0;
                }
            });
            document.getElementById('selected-total').innerHTML = `<strong>Rp ${formatRupiah(subtotal)}</strong>`;
            document.getElementById('cart-subtotal').innerText = formatRupiah(subtotal);
        }

        if (selectAll) {
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
                updateSelectedTotal();
            });
        }
        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                const allChecked = Array.from(checkboxes).every(c => c.checked);
                if (selectAll) selectAll.checked = allChecked;
                updateSelectedTotal();
            });
        });

        document.querySelectorAll('.quantity-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const action = this.dataset.action;
                const itemId = this.dataset.itemId;
                const input = document.querySelector(`.quantity-input[data-item-id="${itemId}"]`);
                let value = parseInt(input.value);

                if (action === 'increase') value++;
                else if (action === 'decrease' && value > 1) value--;
                input.value = value;
                updateItemTotal(itemId, value);
            });
        });
        document.querySelectorAll('.quantity-input').forEach(inp => {
            inp.addEventListener('change', function() {
                const itemId = this.dataset.itemId;
                let value = parseInt(this.value);
                if (isNaN(value) || value < 1) {
                    value = 1;
                    this.value = 1;
                }
                updateItemTotal(itemId, value);
            });
        });

        window.confirmDelete = function(itemId) {
            if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
                document.getElementById(`delete-form-${itemId}`).submit();
            }
        };

        // Logic Baru: Perbarui kuantitas saat checkout
        checkoutForm.addEventListener('submit', function(e) {
            const selectedCheckboxes = Array.from(checkboxes).filter(cb => cb.checked);
            if (selectedCheckboxes.length === 0) {
                e.preventDefault();
                alert('Pilih setidaknya satu produk untuk checkout.');
                return;
            }

            e.preventDefault(); // Mencegah form disubmit secara langsung

            const formData = new FormData();
            selectedCheckboxes.forEach(cb => {
                const itemId = cb.value;
                const quantity = document.querySelector(`.quantity-input[data-item-id="${itemId}"]`).value;
                formData.append(`quantities[${itemId}]`, quantity);
            });

            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'PATCH');

            fetch('{{ route("customer.carts.update_selected") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Setelah berhasil diperbarui, submit form checkout
                    checkoutForm.submit();
                } else {
                    alert(data.message || 'Terjadi kesalahan saat memperbarui produk.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memperbarui produk.');
            });
        });

        updateSelectedTotal();

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

@endsection
