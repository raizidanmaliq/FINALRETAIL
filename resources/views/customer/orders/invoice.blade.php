<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Pesanan</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; margin: 20px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; font-size: 18px; }
        .info { margin-bottom: 15px; }
        .info p { margin: 3px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f2f2f2; text-align: center; }
        td { text-align: center; }
        .total-row td { font-weight: bold; }
        .footer { margin-top: 20px; text-align: center; font-size: 11px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Nota Pesanan</h2>
        <p><strong>Kode Pesanan:</strong> {{ $order->order_code ?? $order->id }}</p>
    </div>

    <div class="info">
        <p><strong>Nama:</strong> {{ $order->receiver_name ?? 'Tidak Diketahui' }}</p>
        <p><strong>Email:</strong> {{ $order->receiver_email ?? '-' }}</p>
        <p><strong>Telepon:</strong> {{ $order->receiver_phone ?? '-' }}</p>
        <p><strong>Alamat:</strong> {{ $order->shipping_address ?? $order->receiver_address ?? 'Tidak ada' }}</p>
        <p><strong>Tanggal Pesan:</strong> {{ $order->created_at->format('d/m/Y') }}</p>
        @if($order->payment)
            <p><strong>Metode Pembayaran:</strong> {{ Str::title(str_replace('_', ' ', $order->payment->payment_method)) }}</p>
        @else
            <p><strong>Metode Pembayaran:</strong> Belum ada informasi</p>
        @endif
        <p><strong>Status Pesanan:</strong> {{ ucfirst($order->order_status) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 40%;">Produk</th>
                <th style="width: 15%;">Jumlah</th>
                <th style="width: 20%;">Harga</th>
                <th style="width: 25%;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td style="text-align:left;">
                    {{ $item->product->name ?? 'Produk tidak ditemukan' }}
                    @if($item->color || $item->size)
                        <br><small>
                            @if($item->color) Warna: {{ $item->color }}@endif
                            @if($item->color && $item->size) | @endif
                            @if($item->size) Ukuran: {{ $item->size }}@endif
                        </small>
                    @endif
                </td>
                <td>{{ $item->quantity }}</td>
                <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($item->subtotal ?? ($item->quantity * $item->price), 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3" style="text-align:right;">Total Harga</td>
                <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Terima kasih telah berbelanja!</p>
    </div>
</body>
</html>
