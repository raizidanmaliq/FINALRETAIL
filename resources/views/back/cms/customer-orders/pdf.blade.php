<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Pesanan #{{ $order->order_code }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
        }
        .details, .items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .details td, .items th, .items td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .details td:first-child, .items th {
            background-color: #f2f2f2;
        }
        .footer {
            text-align: right;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Detail Pesanan Pelanggan</h2>
        <h1>{{ $order->order_code }}</h1>
    </div>

    <table class="details">
        <tr>
            <td>Pelanggan: </td>
            <td>{{ $order->customer->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td>Status Pesanan: </td>
            <td>{{ ucfirst($order->order_status) }}</td>
        </tr>
        <tr>
            <td>Tanggal Pesan: </td>
            <td>{{ $order->created_at->format('d M Y') }}</td>
        </tr>
        <tr>
            <td>Alamat Pengiriman: </td>
            <td>{{ $order->shipping_address ?? 'Tidak ada' }}</td>
        </tr>
    </table>

    <h3>Item Pesanan</h3>
    <table class="items">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Kuantitas</th>
                <th>Harga Satuan</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'N/A' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <h3>Total Harga: Rp{{ number_format($order->total_price, 0, ',', '.') }}</h3>
    </div>

</body>
</html>
