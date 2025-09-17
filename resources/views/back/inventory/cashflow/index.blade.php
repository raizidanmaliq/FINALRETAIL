@extends('layouts.admin.app')

@section('header')
<header class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Dashboard Keuangan</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">Dashboard Keuangan</li>
            </ol>
        </div>
    </div>
</header>
@endsection

@section('content')
<div class="content-body">

    {{-- ===================== SUMMARY BOX ===================== --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Ringkasan Arus Kas</h5>
            <form action="{{ route('admin.inventory.cashflow') }}" method="GET" class="form-inline ml-auto">
                <div class="form-group mr-2">
                    <label for="summary_start_date" class="mr-2">Dari</label>
                    <input type="date" name="summary_start_date" id="summary_start_date"
                           class="form-control form-control-sm"
                           value="{{ $data['summary_start_date'] ?? '' }}">
                </div>
                <div class="form-group mr-2">
                    <label for="summary_end_date" class="mr-2">Sampai</label>
                    <input type="date" name="summary_end_date" id="summary_end_date"
                           class="form-control form-control-sm"
                           value="{{ $data['summary_end_date'] ?? '' }}">
                </div>
                <button type="submit" class="btn btn-sm btn-primary">Terapkan</button>
            </form>
        </div>
        <div class="card-body">
            <div class="row">
                {{-- Total Pemasukan --}}
                <div class="col-md-4 mb-3">
                    <div class="card text-white bg-success shadow-sm h-100 position-relative overflow-hidden">
                        <div class="card-body">
                            <h4 class="fw-bold">Rp {{ number_format($data['total_income'], 0, ',', '.') }}</h4>
                            <span>Total Pemasukan</span>
                            <i class="fas fa-arrow-down position-absolute"
                               style="font-size:80px; opacity:0.15; right:10px; bottom:10px;"></i>
                        </div>
                    </div>
                </div>
                {{-- Total Pengeluaran --}}
                <div class="col-md-4 mb-3">
                    <div class="card text-white bg-danger shadow-sm h-100 position-relative overflow-hidden">
                        <div class="card-body">
                            <h4 class="fw-bold">Rp {{ number_format($data['total_expenses'], 0, ',', '.') }}</h4>
                            <span>Total Pengeluaran</span>
                            <i class="fas fa-arrow-up position-absolute"
                               style="font-size:80px; opacity:0.15; right:10px; bottom:10px;"></i>
                        </div>
                    </div>
                </div>
                {{-- Arus Kas Bersih --}}
                <div class="col-md-4 mb-3">
                    <div class="card text-white bg-primary shadow-sm h-100 position-relative overflow-hidden">
                        <div class="card-body">
                            <h4 class="fw-bold">Rp {{ number_format($data['net_cash_flow'], 0, ',', '.') }}</h4>
                            <span>Arus Kas Bersih</span>
                            <i class="fas fa-exchange-alt position-absolute"
                               style="font-size:80px; opacity:0.15; right:10px; bottom:10px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== CHART ===================== --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Tren Arus Kas</h5>
            <div class="ml-auto">
                <a href="?chart_period=7_days"
                   class="btn btn-sm btn-outline-primary {{ request('chart_period', '7_days') == '7_days' ? 'active' : '' }}">
                   7 Hari
                </a>
                <a href="?chart_period=30_days"
                   class="btn btn-sm btn-outline-primary {{ request('chart_period') == '30_days' ? 'active' : '' }}">
                   30 Hari
                </a>
            </div>
        </div>
        <div class="card-body">
            <div style="height: 350px;">
                <canvas id="cashFlowChart"></canvas>
            </div>
            <small class="text-muted d-block mt-2">
                Periode: {{ $data['chart_date_range']['start'] }} s/d {{ $data['chart_date_range']['end'] }}
            </small>
        </div>
    </div>

    {{-- ===================== TABLE ===================== --}}
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Ringkasan Pesanan per Kategori</h5>
            <form action="{{ route('admin.inventory.cashflow') }}" method="GET" class="form-inline ml-auto">
                <input type="hidden" name="summary_start_date" value="{{ $data['summary_start_date'] ?? '' }}">
                <input type="hidden" name="summary_end_date" value="{{ $data['summary_end_date'] ?? '' }}">
                <input type="hidden" name="chart_period" value="{{ $data['chart_period'] ?? '' }}">
                <div class="form-group mr-2">
                    <label for="table_start_date" class="mr-2">Dari</label>
                    <input type="date" name="table_start_date" id="table_start_date"
                           class="form-control form-control-sm"
                           value="{{ $data['table_start_date'] ?? '' }}">
                </div>
                <div class="form-group mr-2">
                    <label for="table_end_date" class="mr-2">Sampai</label>
                    <input type="date" name="table_end_date" id="table_end_date"
                           class="form-control form-control-sm"
                           value="{{ $data['table_end_date'] ?? '' }}">
                </div>
                <button type="submit" class="btn btn-sm btn-secondary">Terapkan</button>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="transactionTable" class="table table-bordered table-striped table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>No.</th>
                            <th>Kategori</th>
                            <th>Total Transaksi</th>
                            <th>Pending</th>
                            <th>Diproses</th>
                            <th>Dikirim</th>
                            <th>Selesai</th>
                            <th>Dibatalkan</th>
                            <th>Total Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['category_summary'] as $index => $summary)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $summary->category_name }}</td>
                                <td>{{ number_format($summary->total_transactions) }}</td>
                                <td class="text-warning">{{ number_format($summary->pending) }}</td>
                                <td class="text-info">{{ number_format($summary->processing) }}</td>
                                <td class="text-primary">{{ number_format($summary->shipped) }}</td>
                                <td class="text-success">{{ number_format($summary->completed) }}</td>
                                <td class="text-danger">{{ number_format($summary->cancelled) }}</td>
                                <td><strong>Rp {{ number_format($summary->total_revenue, 0, ',', '.') }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script>
$(function () {
    // ChartJS
    const chartData = @json($data['chart_data']);
    if (chartData.length > 0) {
        const labels = chartData.map(item => item.date);
        const income = chartData.map(item => item.income);
        const expenses = chartData.map(item => item.expenses);
        const net = chartData.map(item => item.net);

        const ctx = document.getElementById('cashFlowChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    { label: 'Pemasukan', data: income, borderColor: '#28a745', backgroundColor: 'rgba(40,167,69,0.15)', fill: true, tension: 0.4 },
                    { label: 'Pengeluaran', data: expenses, borderColor: '#dc3545', backgroundColor: 'rgba(220,53,69,0.15)', fill: true, tension: 0.4 },
                    { label: 'Arus Kas Bersih', data: net, borderColor: '#007bff', backgroundColor: 'rgba(0,123,255,0.15)', fill: true, tension: 0.4 }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { position: 'top' },
                    tooltip: { callbacks: { label: ctx => 'Rp ' + ctx.raw.toLocaleString() } }
                },
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    // DataTables
    $('#transactionTable').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        responsive: true,
        pageLength: 5,
        lengthMenu: [5, 10, 25, 50]
    });
});
</script>
@endpush
