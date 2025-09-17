<?php
/**
 * @var \App\Models\Supplier[] $suppliers
 */
?>
@extends('layouts.admin.app')

@section('header')
<header class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Pesanan Supplier</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">Pesanan Supplier</li>
            </ol>
        </div>
    </div>
</header>
@endsection

@section('content')
<section class="card">
    <article class="card-header">
        <div class="float-right">
            <div class="btn-group">
                <a href="{{ route('admin.purchase-orders.create') }}" class="btn text-white" style="background-color: #9B4141;"><i class="fa fa-plus"></i> Tambah Pemesanan</a>
            </div>
        </div>
    </article>
    <article class="card-body">
        {{-- Filter Section --}}
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="date-range-filter">Filter Tanggal Pesan</label>
                    <input type="text" id="date-range-filter" class="form-control" placeholder="Pilih rentang tanggal...">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="supplier-filter">Filter Supplier</label>
                    <select id="supplier-filter" class="form-control">
                        <option value="">Semua Supplier</option>
                        {{-- Ambil data supplier dari controller dan tampilkan --}}
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        {{-- End Filter Section --}}

        <table class="table table-striped table-bordered" id="datatable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>No. PO</th>
                    <th>Nama Supplier</th>
                    <th>Tanggal Pesan</th>
                    <th>Estimasi Tiba</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </article>
</section>
@endsection

@push('js')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<script>
    $(function() {
        const table = $('#datatable').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            pageLength: 10,
            pagingType: "simple_numbers",
            ajax: {
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                url: "{{ route('admin.purchase-orders.data') }}",
                dataType: "json",
                type: "GET",
                data: function(d) {
                    d.from_date = $('#date-range-filter').val() ? $('#date-range-filter').data('daterangepicker').startDate.format('YYYY-MM-DD') : '';
                    d.to_date = $('#date-range-filter').val() ? $('#date-range-filter').data('daterangepicker').endDate.format('YYYY-MM-DD') : '';
                    d.supplier_id = $('#supplier-filter').val();
                }
            },
            columns: [
                { data: 'id', name: 'id', className: "text-center align-middle"},
                { data: 'po_number', name: 'po_number', className: "align-middle" },
                { data: 'supplier_name', name: 'supplier_name', className: "align-middle" },
                { data: 'order_date', name: 'order_date', className: "align-middle" },
                { data: 'arrival_estimate_date', name: 'arrival_estimate_date', className: "align-middle" },
                { data: 'status', name: 'status', className: "align-middle" },
                { data: 'actions', name: 'actions', className: "align-middle", sortable: false, searchable: false },
            ]
        });

        // Initialize Date Range Picker
        $('#date-range-filter').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear',
                format: 'DD/MM/YYYY',
                separator: ' - ',
                applyLabel: 'Apply',
                cancelLabel: 'Cancel',
                fromLabel: 'From',
                toLabel: 'To',
                customRangeLabel: 'Custom',
                weekLabel: 'W',
                daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                firstDay: 1
            }
        });

        $('#date-range-filter').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            table.ajax.reload();
        });

        $('#date-range-filter').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            table.ajax.reload();
        });

        // Event listener for supplier filter
        $('#supplier-filter').on('change', function() {
            table.ajax.reload();
        });
    });
</script>
@endpush
