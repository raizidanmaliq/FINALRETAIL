@extends('layouts.admin.app')

@section('header')
    <header class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Tambah Produk Baru</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.inventory.dashboard') }}">Stok</a>
                    </li>
                    <li class="breadcrumb-item active">Tambah Produk</li>
                </ol>
            </div>
        </div>
    </header>
@endsection

@section('content')
    <div class="content-body">
        <form method="POST" action="{{ route('admin.inventory.products.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="form-row">
                        {{-- Nama Produk --}}
                        <div class="form-group col-md-6">
                            <label for="name">Nama Produk</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- SKU --}}
                        <div class="form-group col-md-6">
                            <label for="sku">SKU / Barcode</label>
                            <input type="text" name="sku" class="form-control" value="{{ old('sku') }}" required>
                            @error('sku') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- Kategori --}}
                        <div class="form-group col-md-6">
                            <label for="category_id">Kategori</label>
                            <select name="category_id" class="form-control" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected':'' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- Satuan --}}
                        <div class="form-group col-md-6">
                            <label for="unit">Satuan</label>
                            <input type="text" name="unit" class="form-control" value="{{ old('unit') }}" required>
                            @error('unit') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- Harga Modal --}}
                        <div class="form-group col-md-6">
                            <label for="cost_price">Harga Modal</label>
                            <input type="number" name="cost_price" class="form-control" value="{{ old('cost_price') }}" required>
                            @error('cost_price') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- Harga Jual --}}
                        <div class="form-group col-md-6">
                            <label for="selling_price">Harga Jual</label>
                            <input type="number" name="selling_price" class="form-control" value="{{ old('selling_price') }}" required>
                            @error('selling_price') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- Stok Awal --}}
                        <div class="form-group col-md-6">
                            <label for="inventory">Stok Awal</label>
                            <input type="number" name="inventory" class="form-control" value="{{ old('inventory') }}" required>
                            @error('inventory') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- Foto Produk --}}
                        <div class="form-group col-md-6">
                            <label for="image">Foto Produk</label>
                            <input type="file" name="image" class="form-control">
                            @error('image') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- Deskripsi --}}
                        <div class="form-group col-md-12">
                            <label for="description">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                            @error('description') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- Label Promo --}}
                        <div class="form-group col-md-12">
                            <label for="promo_label">Label Promo (Opsional)</label>
                            <select name="promo_label" class="form-control">
                                <option value="">-- Tidak Ada --</option>
                                <option value="Bestseller" {{ old('promo_label') == 'Bestseller' ? 'selected' : '' }}>Bestseller</option>
                                <option value="Limited Stock" {{ old('promo_label') == 'Limited Stock' ? 'selected' : '' }}>Limited Stock</option>
                                <option value="New Arrival" {{ old('promo_label') == 'New Arrival' ? 'selected' : '' }}>New Arrival</option>
                                <option value="Flash Sale" {{ old('promo_label') == 'Flash Sale' ? 'selected' : '' }}>Flash Sale</option>
                            </select>
                            @error('promo_label') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-success float-right" style="background-color: #9B4141;">
                        <i class="la la-check-square-o"></i> Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
