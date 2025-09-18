@extends('layouts.common.app')

@section('content')

<div class="container mt-4 mb-5">
    <h5 class="fw-bold mb-4">Keranjang Belanja</h5> @if($cartItems->isNotEmpty())
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
                                <th scope="col" class="ps-3">Produk</th> <th scope="col" class="text-end pe-3">Harga</th> <th scope="col" class="text-center">Kuantitas</th>
                                <th scope="col" class="text-end pe-3">Total</th> <th scope="col" class="text-center">Aksi</th>
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
                                    <td class="align-middle ps-3"> <input type="checkbox"
                                               name="cart_ids[]"
                                               value="{{ $item->id }}"
                                               class="cart-checkbox"
                                               data-price="{{ $itemTotal }}"
                                               data-original-price="{{ $itemPrice }}">
                                    </td>
                                    <td class="align-middle"> <div class="d-flex align-items-center"> <img src="{{ $imagePath }}"
                                                 alt="{{ $item->product->name ?? $item->product_name ?? '' }}"
                                                 class="img-fluid rounded me-3"
                                                 style="width:80px; height:80px; object-fit:cover;"
                                                 onerror="this.src='{{ asset('images/no-image.png') }}'">
                                            <div>
                                                <h6 class="mb-2 fw-bold">{{ $item->product->name ?? $item->product_name ?? '' }}</h6> <div class="mb-1"><small>Ukuran: {{ $item->variant->size ?? $item->size ?? '-' }}</small></div> <div><small>Warna: {{ $item->variant->color ?? $item->color ?? '-' }}</small></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-end pe-3"> <div class="fw-medium">Rp {{ number_format($itemPrice, 0, ',', '.') }}</div> </td>
                                    <td class="align-middle text-center"> <div class="input-group input-group-sm quantity-control mx-auto" style="width:120px;">
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
                                    <td class="fw-bold align-middle text-end pe-3" id="total-{{ $item->id }}"> Rp {{ number_format($itemTotal, 0, ',', '.') }}
                                    </td>
                                    <td class="align-middle text-center"> <button type="button" class="btn btn-sm p-0" onclick="confirmDelete('{{ $item->id }}')" style="color: #A34A4A;">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-top" style="border-color: #A34A4A !important;">
                                <td colspan="4" class="text-end fw-bold py-3 pe-3">Total untuk Produk Dipilih</td> <td colspan="2" id="selected-total" class="fw-bold text-end py-3 pe-3"><strong>Rp 0</strong></td> </tr>
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
    });
</script>
@endpush

@endsection
