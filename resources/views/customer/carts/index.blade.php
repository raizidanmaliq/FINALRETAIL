@extends('layouts.customer.app')

@section('header')
<header class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Keranjang Belanja</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Keranjang</li>
            </ol>
        </div>
    </div>
</header>
@endsection

@section('content')
<section class="card">
    {{-- Header Card --}}
    <article class="card-header">
        <div class="float-right">
            {{-- Tempatkan tombol aksi tambahan di sini jika diperlukan --}}
        </div>
    </article>

    {{-- Body Card --}}
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

        @if($cartItems->isNotEmpty())
            <!-- Form Checkout (Form Utama) -->
            <form id="checkoutForm" action="{{ route('customer.checkout.prepare') }}" method="POST">
                @csrf
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th width="5%">
                                <input type="checkbox" id="selectAll">
                            </th>
                            <th>Produk</th>
                            <th>Harga Satuan</th>
                            <th>Jumlah</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cartItems as $item)
                            @php
                                $itemTotal = $item->price_snapshot * $item->quantity;
                            @endphp
                            <tr>
                                <td class="align-middle">
                                    <input type="checkbox" name="cart_ids[]" value="{{ $item->id }}" class="cart-checkbox" data-price="{{ $itemTotal }}">
                                </td>
                                <td class="align-middle">{{ $item->product->name }}</td>
                                <td class="align-middle">Rp {{ number_format($item->price_snapshot, 0, ',', '.') }}</td>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <button type="button" class="btn btn-sm btn-outline-secondary quantity-btn" data-action="decrease" data-item-id="{{ $item->id }}">-</button>
                                        <input type="number" name="quantities[{{ $item->id }}]" value="{{ $item->quantity }}" min="1" class="form-control mx-2 quantity-input" data-item-id="{{ $item->id }}" style="width: 70px;">
                                        <button type="button" class="btn btn-sm btn-outline-secondary quantity-btn" data-action="increase" data-item-id="{{ $item->id }}">+</button>
                                    </div>
                                </td>
                                <td class="align-middle item-total" id="total-{{ $item->id }}">
                                    Rp {{ number_format($itemTotal, 0, ',', '.') }}
                                </td>
                                <td class="align-middle">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $item->id }})">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-right"><strong>Total untuk Produk Dipilih</strong></td>
                            <td colspan="2" id="selected-total"><strong>Rp 0</strong></td>
                        </tr>
                    </tfoot>
                </table>

                <div class="d-flex justify-content-between mt-3">
                    <button type="button" id="updateSelectedBtn" class="btn btn-primary">
                        <i class="fas fa-sync-alt"></i> Update Jumlah yang Dipilih
                    </button>
                    <button type="submit" class="btn btn-success" id="checkoutSelected">
                        <i class="fas fa-shopping-cart"></i> Checkout Selected
                    </button>
                </div>
            </form>

            <!-- Form Delete (DI LUAR Form Checkout) -->
            @foreach($cartItems as $item)
                <form action="{{ route('customer.carts.remove', $item) }}" method="POST" id="delete-form-{{ $item->id }}" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            @endforeach
        @else
            <p class="text-center">
                Keranjang belanja Anda kosong.
                <a href="{{ route('front.catalog.index') }}">Mulai belanja sekarang!</a>
            </p>
        @endif
    </article>
</section>
@endsection

@push('js')
<script>
    function confirmDelete(itemId) {
        if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
            document.getElementById('delete-form-' + itemId).submit();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.cart-checkbox');

        // Select all
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
            updateSelectedTotal();
        });

        // Periksa apakah semua checkbox dicentang
        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                const allChecked = Array.from(checkboxes).every(c => c.checked);
                selectAll.checked = allChecked;
                updateSelectedTotal();
            });
        });

        // Tombol plus minus
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
                updateSelectedTotal();
            });
        });

        // Input manual
        document.querySelectorAll('.quantity-input').forEach(inp => {
            inp.addEventListener('change', function() {
                const itemId = this.dataset.itemId;
                let value = parseInt(this.value);
                if (isNaN(value) || value < 1) {
                    value = 1;
                    this.value = 1;
                }
                updateItemTotal(itemId, value);
                updateSelectedTotal();
            });
        });

        // Update selected via AJAX
        document.getElementById('updateSelectedBtn').addEventListener('click', function() {
            const selectedItems = Array.from(checkboxes).filter(cb => cb.checked);
            if (selectedItems.length === 0) {
                alert('Pilih setidaknya satu produk untuk diupdate.');
                return;
            }

            const formData = new FormData();
            selectedItems.forEach(cb => {
                const itemId = cb.value;
                const qty = document.querySelector(`.quantity-input[data-item-id="${itemId}"]`).value;
                formData.append(`quantities[${itemId}]`, qty);
            });

            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'PATCH');

            fetch('{{ route("customer.carts.update_selected") }}', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    alert('Jumlah produk berhasil diperbarui.');
                    location.reload();
                } else {
                    alert('Terjadi kesalahan saat memperbarui jumlah produk.');
                }
            })
            .catch(err => {
                console.error('Error:', err);
                alert('Terjadi kesalahan saat memperbarui jumlah produk.');
            });
        });

        // Validasi checkout
        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            const selectedItems = Array.from(checkboxes).filter(cb => cb.checked);
            if (selectedItems.length === 0) {
                e.preventDefault();
                alert('Pilih setidaknya satu produk untuk checkout.');
                return false;
            }
            this.method = 'POST';
            return true;
        });

        // Fungsi total item
        function updateItemTotal(itemId, quantity) {
            const checkbox = document.querySelector(`.cart-checkbox[value="${itemId}"]`);
            const pricePerUnit = parseFloat(checkbox.dataset.originalPrice);
            const total = pricePerUnit * quantity;

            checkbox.dataset.price = total;
            document.getElementById(`total-${itemId}`).textContent = `Rp ${formatRupiah(total)}`;
        }

        // Fungsi total selected
        function updateSelectedTotal() {
            let total = 0;
            checkboxes.forEach(cb => {
                if (cb.checked) total += parseFloat(cb.dataset.price);
            });
            document.getElementById('selected-total').innerHTML = `<strong>Rp ${formatRupiah(total)}</strong>`;
        }

        // Format Rupiah
        function formatRupiah(amount) {
            return amount.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Init data attribute
        checkboxes.forEach(cb => {
            const itemId = cb.value;
            const quantityInput = document.querySelector(`.quantity-input[data-item-id="${itemId}"]`);
            const quantity = parseInt(quantityInput.value);
            const totalPrice = parseFloat(cb.dataset.price);
            const pricePerUnit = totalPrice / quantity;
            cb.dataset.originalPrice = pricePerUnit;
        });

        // Init selected total
        updateSelectedTotal();
    });
</script>
@endpush

@push('css')
<style>
    #selected-total {
        color: #28a745;
        font-size: 1.1em;
    }

    .quantity-input {
        text-align: center;
    }

    .quantity-btn {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endpush
