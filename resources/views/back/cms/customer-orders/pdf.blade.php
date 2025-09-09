<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Pesanan #{{ $order->order_code }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1, .header h2 {
            margin: 0;
            color: #444;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        .details-table, .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .details-table td, .items-table th, .items-table td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        .details-table td:first-child, .items-table th {
            background-color: #f7f7f7;
            font-weight: bold;
            width: 30%;
        }
        .items-table th {
            text-align: center;
        }
        .items-table td {
            text-align: center;
        }
        .items-table td:first-child {
            text-align: left;
        }
        .total-row {
            background-color: #f7f7f7;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-left {
            text-align: left;
        }
        .total-price {
            font-size: 16px;
            color: #28a745;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Detail Pesanan Pelanggan</h2>
        <h1>{{ $order->order_code }}</h1>
    </div>

    <div class="section-title">Informasi Pengiriman</div>
    <table class="details-table">
        <tr>
            <td>Nama Penerima:</td>
            <td>{{ $order->receiver_name }}</td>
        </tr>
        <tr>
            <td>Email Penerima:</td>
            <td>{{ $order->receiver_email }}</td>
        </tr>
        <tr>
            <td>Telepon Penerima:</td>
            <td>{{ $order->receiver_phone }}</td>
        </tr>
        <tr>
            <td>Alamat Pengiriman:</td>
            <td>{{ $order->receiver_address ?? 'Tidak ada' }}</td>
        </tr>
        <tr>
            <td>Tanggal Pesan:</td>
            <td>{{ $order->created_at->format('d M Y H:i') }}</td>
        </tr>
        <tr>
            <td>Status Pesanan:</td>
            <td>{{ ucfirst($order->order_status) }}</td>
        </tr>
    </table>

    <div class="section-title">Item Pesanan</div>
    <table class="items-table">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Warna</th>
                <th>Ukuran</th>
                <th>Kuantitas</th>
                <th>Harga Satuan</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td class="text-left">{{ $item->product->name ?? 'N/A' }}</td>
                    <td>{{ $item->color ?? '-' }}</td>
                    <td>{{ $item->size ?? '-' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td class="text-right">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="text-right">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5" class="text-right">Total Keseluruhan:</td>
                <td class="text-right total-price">Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

</body>
</html>
