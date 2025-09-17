<!DOCTYPE html>
<html>
<head>
    <title>Purchase Order {{ $po->po_number }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; padding: 0; }
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 8px; text-align: left; }
        .details-table th, .details-table td { border: none; }
    </style>
</head>
<body>
    <div class="header">
        <h1>PURCHASE ORDER</h1>
        <h2>{{ $po->po_number }}</h2>
    </div>

    <table class="details-table">
        <tr>
            <th width="20%">Supplier:</th>
            <td>{{ $po->supplier->name ?? '-' }}</td>
        </tr>
        <tr>
            <th>Email Supplier:</th>
            <td>{{ $po->supplier->email ?? '-' }}</td>
        </tr>
        <tr>
            <th>Telepon Supplier:</th>
            <td>{{ $po->supplier->phone ?? '-' }}</td>
        </tr>
        <tr>
            <th>Alamat Supplier:</th>
            <td>{{ $po->supplier->address ?? '-' }}</td>
        </tr>
        <tr>
            <th>Tanggal Pemesanan:</th>
            <td>{{ \Carbon\Carbon::parse($po->order_date)->format('d F Y') }}</td>
        </tr>
        <tr>
            <th>Estimasi Kedatangan:</th>
            <td>{{ $po->arrival_estimate_date ? \Carbon\Carbon::parse($po->arrival_estimate_date)->format('d F Y') : '-' }}</td>
        </tr>
        <tr>
            <th>Status:</th>
            <td>{{ Str::title($po->status) }}</td>
        </tr>
    </table>

    <br>
    <h4>Detail Produk</h4>
    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Harga Per Unit</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($po->details as $detail)
                <tr>
                    <td>{{ $detail->product->name }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>Rp {{ number_format($detail->unit_price, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($detail->quantity * $detail->unit_price, 0, ',', '.') }}</td>
                </tr>
                @php $total += ($detail->quantity * $detail->unit_price); @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right; font-weight: bold;">Total:</td>
                <td>Rp {{ number_format($total, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

</body>
</html>
