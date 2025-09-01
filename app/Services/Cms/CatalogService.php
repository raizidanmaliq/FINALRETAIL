<?php

namespace App\Services\Cms;

use App\Models\Inventory\Product;
use Yajra\DataTables\Facades\DataTables;

class CatalogService
{
    /**
     * Mengambil daftar produk untuk ditampilkan di katalog.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function data($request)
    {
        $products = Product::with('category')->get();

        return DataTables::of($products)
            ->addColumn('image', function ($product) {
                // Perbaikan: Gunakan jalur yang benar yang mengarah ke direktori 'back_assets'
                $imageUrl = $product->image ? asset($product->image) : asset('path/to/placeholder.jpg');
                return '<img src="' . $imageUrl . '" width="100" height="100">';
            })
            ->addColumn('status_display', function ($product) {
                return $product->is_displayed ?
                    '<span class="badge bg-success">Ditampilkan</span>' :
                    '<span class="badge bg-secondary">Tidak Ditampilkan</span>';
            })
            ->addColumn('actions', function ($product) {
                $toggleAction = $product->is_displayed ? 'hide' : 'show';
                $toggleText = $product->is_displayed ? 'Hapus dari Katalog' : 'Tambahkan ke Katalog';
                $toggleClass = $product->is_displayed ? 'btn-danger' : 'btn-success';

                return '
                    <div class="btn-group">
                        <button class="btn btn-sm ' . $toggleClass . ' btn-toggle-display" data-id="' . $product->id . '" data-action="' . $toggleAction . '">' . $toggleText . '</button>
                    </div>
                ';
            })
            ->rawColumns(['image', 'status_display', 'actions'])
            ->addIndexColumn() // Tambahkan baris ini
            ->toJson();
    }

    /**
     * Memperbarui status produk untuk ditampilkan di katalog.
     *
     * @param  \App\Models\Inventory\Product  $product
     * @param  bool  $isDisplayed
     * @return \App\Models\Inventory\Product|null
     */
    public function updateDisplayStatus(Product $product, $isDisplayed)
    {
        try {
            $product->update(['is_displayed' => $isDisplayed]);
            return $product;
        } catch (\Exception $e) {
            // Tangani error
            return null;
        }
    }
}
