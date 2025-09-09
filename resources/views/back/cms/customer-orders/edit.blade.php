@extends('layouts.admin.app')

@section('header')
<header class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Edit Pesanan Pelanggan</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.cms.customer-orders.index') }}">Pesanan Pelanggan</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
    </div>
</header>
@endsection

@section('content')
<section class="card">
    <article class="card-body">
        <form action="{{ route('admin.cms.customer-orders.update', $customer_order->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="order_code">Kode Pesanan</label>
                        <input type="text" class="form-control" value="{{ $customer_order->order_code }}" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="order_status">Status Pesanan</label>
                        <select name="order_status" id="order_status" class="form-control" required>
                            <option value="pending" {{ $customer_order->order_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $customer_order->order_status == 'processing' ? 'selected' : '' }}>Diproses</option>
                            <option value="shipped" {{ $customer_order->order_status == 'shipped' ? 'selected' : '' }}>Dikirim</option>
                            <option value="completed" {{ $customer_order->order_status == 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="cancelled" {{ $customer_order->order_status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                        @error('order_status')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <hr>
            <h5>Detail Pengiriman</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="customer_id">Pelanggan</label>
                        <select name="customer_id" id="customer_id" class="form-control">
                            <option value="">Pilih Pelanggan (Opsional)</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}"
                                    {{ $customer_order->customer_id == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="receiver_name">Nama Penerima</label>
                        <input type="text" name="receiver_name" id="receiver_name" class="form-control" value="{{ old('receiver_name', $customer_order->receiver_name) }}" required>
                        @error('receiver_name')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="receiver_phone">Telepon Penerima</label>
                        <input type="tel" name="receiver_phone" id="receiver_phone" class="form-control" value="{{ old('receiver_phone', $customer_order->receiver_phone) }}" required>
                        @error('receiver_phone')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="receiver_email">Email Penerima</label>
                        <input type="email" name="receiver_email" id="receiver_email" class="form-control" value="{{ old('receiver_email', $customer_order->receiver_email) }}" required>
                        @error('receiver_email')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="receiver_address">Alamat Pengiriman</label>
                        <textarea name="receiver_address" id="receiver_address" class="form-control" rows="3" required>{{ old('receiver_address', $customer_order->receiver_address) }}</textarea>
                        @error('receiver_address')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <hr>
            <h5>Detail Produk</h5>
            <div id="product-list">
                @foreach($customer_order->items as $index => $item)
                <div class="row product-item mb-2 align-items-end" data-index="{{ $index }}">
                    <div class="col-md-3">
                        <label class="form-label">Produk</label>
                        <select name="products[{{ $index }}][product_id]" class="form-control product-select" required>
                            <option value="">Pilih Produk</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-selling-price="{{ $product->selling_price }}"
                                    {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Warna</label>
                        <select name="products[{{ $index }}][color]" class="form-control color-select">
                            <option value="">Pilih Warna</option>
                            @if($item->color)
                                <option value="{{ $item->color }}" selected>{{ $item->color }}</option>
                            @endif
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Ukuran</label>
                        <select name="products[{{ $index }}][size]" class="form-control size-select">
                            <option value="">Pilih Ukuran</option>
                            @if($item->size)
                                <option value="{{ $item->size }}" selected>{{ $item->size }}</option>
                            @endif
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">Jumlah</label>
                        <input type="number" name="products[{{ $index }}][quantity]" class="form-control quantity-input" min="1" value="{{ old("products.$index.quantity", $item->quantity) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Harga Unit</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control unit-price-display" value="{{ number_format(old("products.$index.unit_price", $item->price), 0, ',', '.') }}" readonly>
                            <input type="hidden" name="products[{{ $index }}][unit_price]" class="unit-price-input" value="{{ old("products.$index.unit_price", $item->price) }}">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger w-100 remove-product"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                @endforeach
            </div>
            <button type="button" id="add-product" class="btn btn-info btn-sm mb-3"><i class="fa fa-plus"></i> Tambah Produk</button>

            <div class="form-group mt-3">
                <label>Total Harga</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="text" name="total_price_display" id="total-price-display" class="form-control" value="0" readonly>
                    <input type="hidden" name="total_price" id="total-price-input" value="0">
                </div>
            </div>

            <hr>
            <h5>Detail Pembayaran</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="payment_date">Tanggal Pembayaran</label>
                        <input type="date" name="payment_date" id="payment_date" class="form-control" value="{{ old('payment_date', $customer_order->payment->payment_date ?? '') }}">
                        @error('payment_date')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="payment_method">Metode Pembayaran</label>
                        <select name="payment_method" id="payment_method" class="form-control">
                            <option value="">Pilih Metode</option>
                            <option value="bank_transfer" {{ ($customer_order->payment->payment_method ?? '') == 'bank_transfer' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="ewallet" {{ ($customer_order->payment->payment_method ?? '') == 'ewallet' ? 'selected' : '' }}>E-wallet</option>
                            <option value="manual" {{ ($customer_order->payment->payment_method ?? '') == 'manual' ? 'selected' : '' }}>Manual</option>
                        </select>
                        @error('payment_method')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="proof_of_payment">Bukti Pembayaran</label>
                        <input type="file" name="proof_of_payment" id="proof_of_payment" class="form-control" accept="image/*">
                        @if($customer_order->payment && $customer_order->payment->proof)
                            <small class="form-text text-muted mt-2">Bukti yang sudah diunggah: <a href="{{ asset($customer_order->payment->proof) }}" target="_blank">Lihat Bukti</a></small>
                        @endif
                        @error('proof_of_payment')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-danger float-right"
                        style="background-color:#9B4141; border-color:#9B4141; color:#fff;">
                    <i class="la la-check-square-o"></i> Update
                </button>
            </div>
        </form>
    </article>
</section>
@endsection

@push('js')
<script>
    // Inisialisasi productIndex dengan jumlah item yang sudah ada
    let productIndex = {{ $customer_order->items->count() }};

    function formatCurrency(value) {
        return new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0 }).format(value);
    }

    function updateProductPrice(element) {
        const productItem = element.closest('.product-item');
        const selectedOption = element.find('option:selected');
        const price = selectedOption.data('selling-price') || 0;

        productItem.find('.unit-price-input').val(price);
        productItem.find('.unit-price-display').val(formatCurrency(price));
    }

    function updateTotal() {
        let grandTotal = 0;
        $('.product-item').each(function() {
            const quantity = parseFloat($(this).find('.quantity-input').val()) || 0;
            const unitPrice = parseFloat($(this).find('.unit-price-input').val()) || 0;
            grandTotal += quantity * unitPrice;
        });
        $('#total-price-input').val(grandTotal);
        $('#total-price-display').val(formatCurrency(grandTotal));
    }

    // Fungsi untuk mengambil dan mengisi varian produk, dengan opsi untuk mempertahankan nilai yang sudah ada
    function fetchAndSetVariants(productId, productItem, existingColor = null, existingSize = null) {
        if (!productId) {
            productItem.find('.color-select, .size-select').empty().append('<option value="">Pilih ...</option>');
            return;
        }

        const url = `{{ url('admin/cms/customer-orders/products') }}/${productId}/variants`;

        $.get(url, function(data) {
            const colorsSelect = productItem.find('.color-select');
            const sizesSelect = productItem.find('.size-select');

            colorsSelect.empty().append('<option value="">Pilih Warna</option>');
            data.colors.forEach(function(color) {
                const selected = color === existingColor ? 'selected' : '';
                colorsSelect.append(`<option value="${color}" ${selected}>${color}</option>`);
            });

            sizesSelect.empty().append('<option value="">Pilih Ukuran</option>');
            data.sizes.forEach(function(size) {
                const selected = size === existingSize ? 'selected' : '';
                sizesSelect.append(`<option value="${size}" ${selected}>${size}</option>`);
            });
        });
    }

    $(document).ready(function() {
        // Initialsiasi total harga saat halaman dimuat
        updateTotal();

        // Mengisi varian untuk setiap produk yang sudah ada
        $('.product-item').each(function() {
            const productItem = $(this);
            const productId = productItem.find('.product-select').val();
            const existingColor = productItem.find('.color-select option:selected').val();
            const existingSize = productItem.find('.size-select option:selected').val();

            if (productId) {
                fetchAndSetVariants(productId, productItem, existingColor, existingSize);
            }
        });

        // Event listener untuk perubahan produk
        $('#product-list').on('change', '.product-select', function() {
            const productItem = $(this).closest('.product-item');
            const productId = $(this).val();

            updateProductPrice($(this));
            updateTotal();
            // Panggil fungsi tanpa nilai varian yang sudah ada karena ini adalah produk baru
            fetchAndSetVariants(productId, productItem);
        });

        // Event listener untuk perubahan jumlah produk
        $('#product-list').on('input', '.quantity-input', function() {
            updateTotal();
        });

        // Event listener untuk tombol 'Tambah Produk'
        $('#add-product').on('click', function() {
            const newIndex = productIndex++;
            const productHtml = `
                <div class="row product-item mb-2 align-items-end" data-index="${newIndex}">
                    <div class="col-md-3">
                        <label class="form-label">Produk</label>
                        <select name="products[${newIndex}][product_id]" class="form-control product-select" required>
                            <option value="">Pilih Produk</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-selling-price="{{ $product->selling_price }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Warna</label>
                        <select name="products[${newIndex}][color]" class="form-control color-select">
                            <option value="">Pilih Warna</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Ukuran</label>
                        <select name="products[${newIndex}][size]" class="form-control size-select">
                            <option value="">Pilih Ukuran</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">Jumlah</label>
                        <input type="number" name="products[${newIndex}][quantity]" class="form-control quantity-input" min="1" value="1" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Harga Unit</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control unit-price-display" value="0" readonly>
                            <input type="hidden" name="products[${newIndex}][unit_price]" class="unit-price-input" value="0">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger w-100 remove-product"><i class="fa fa-times"></i></button>
                    </div>
                </div>
            `;
            $('#product-list').append(productHtml);

            // Re-attach listeners to the newly added row
            const newProductRow = $(`div[data-index="${newIndex}"]`);
            newProductRow.find('.product-select').on('change', function() {
                updateProductPrice($(this));
                updateTotal();
                fetchAndSetVariants($(this).val(), $(this).closest('.product-item'));
            });
            newProductRow.find('.quantity-input').on('input', function() {
                updateTotal();
            });
        });

        // Event listener untuk tombol 'Hapus Produk'
        $('#product-list').on('click', '.remove-product', function() {
            $(this).closest('.product-item').remove();
            updateTotal();
        });
    });
</script>
@endpush
