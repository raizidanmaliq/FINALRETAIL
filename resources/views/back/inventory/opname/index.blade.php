@extends('layouts.admin.app')

@section('header')
<header class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Perbaharui Stok</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.inventory.dashboard') }}">Stok</a>
                </li>
                <li class="breadcrumb-item active">Perbaharui Stok</li>
            </ol>
        </div>
    </div>
</header>
@endsection

@section('content')
<section class="card">
    {{-- 🔎 Filter & Search --}}
    <article class="card-header">
        <div class="row">
            <div class="col-md-4">
                <label for="filter_category" class="font-weight-bold">Filter Kategori:</label>
                <select id="filter_category" class="form-control">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 offset-md-4">
                <label for="search" class="font-weight-bold">Pencarian:</label>
                <input type="text" id="search" class="form-control"
                    placeholder="Cari produk..." value="{{ request('search') }}">
            </div>
        </div>
    </article>

    {{-- 📋 Form Opname --}}
    <form method="POST" action="{{ route('admin.inventory.opname.store') }}">
        @csrf
        <article class="card-body table-responsive">
            <table class="table table-bordered table-striped" id="opname-table">
                <thead>
                    <tr class="text-center">
                        <th style="width: 50px;">No.</th>
                        <th>Nama Produk</th>
                        <th>Stok Sistem</th>
                        <th style="width: 150px;">Stok Gudang</th>
                        <th>Selisih</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $key => $product)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td>{{ $product->name }}</td>
                            <td class="current-stock text-center">{{ $product->stock }}</td>
                            <td>
                                <input type="number"
                                    name="opname_data[{{ $product->id }}][physical_stock]"
                                    class="form-control physical-stock-input text-center"
                                    data-current-stock="{{ $product->stock }}"
                                    value="{{ $product->stock }}" min="0">
                            </td>
                            <td class="difference-output text-center">0</td>
                            <td>
                                <input type="text"
                                    name="opname_data[{{ $product->id }}][note]"
                                    class="form-control note-input"
                                    maxlength="255">
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Tidak ada produk ditemukan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </article>
        <div class="card-footer text-right">
            <button type="submit" class="btn btn-success" style="background-color: #9B4141;">Simpan Opname</button>
        </div>
    </form>
</section>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        // hitung selisih stok
        function updateValidation(row) {
            const currentStock = parseInt(row.find('.current-stock').text()) || 0;
            const physicalStockInput = row.find('.physical-stock-input');
            const noteInput = row.find('.note-input');

            const physicalStock = parseInt(physicalStockInput.val()) || 0;
            const difference = physicalStock - currentStock;

            row.find('.difference-output').text(difference);

            if (difference !== 0) {
                noteInput.prop('required', true);
            } else {
                noteInput.prop('required', false);
            }
        }

        // realtime update selisih saat ketik
        $('.physical-stock-input').on('input', function() {
            updateValidation($(this).closest('tr'));
        });

        // jalankan validasi awal
        $('#opname-table tbody tr').each(function() {
            updateValidation($(this));
        });

        // reload saat filter kategori berubah
        $('#filter_category').on('change', function() {
            const categoryId = $(this).val();
            const search = $('#search').val();
            let url = "{{ route('admin.inventory.opname.index') }}";
            let params = [];

            if (categoryId) params.push('category_id=' + categoryId);
            if (search) params.push('search=' + search);

            if (params.length) {
                url += '?' + params.join('&');
            }

            window.location.href = url;
        });

        // reload saat tekan Enter di search box
        $('#search').on('keypress', function(e) {
            if (e.which == 13) { // enter
                const categoryId = $('#filter_category').val();
                const search = $('#search').val();
                let url = "{{ route('admin.inventory.opname.index') }}";
                let params = [];

                if (categoryId) params.push('category_id=' + categoryId);
                if (search) params.push('search=' + search);

                if (params.length) {
                    url += '?' + params.join('&');
                }

                window.location.href = url;
            }
        });
    });
</script>
@endpush
