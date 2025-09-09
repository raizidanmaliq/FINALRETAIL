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
        // PENTING: Gunakan 'with' untuk memuat relasi 'images'
        $products = Product::with('category', 'images')->get();

        return DataTables::of($products)
            ->addColumn('image', function ($product) {
                // Ambil gambar pertama dari relasi 'images'
                $firstImage = $product->images->first();

                // Periksa apakah ada gambar dan ambil jalurnya
                if ($firstImage && $firstImage->image_path) {
                    $imageUrl = asset($firstImage->image_path);
                    return '<img src="' . $imageUrl . '" width="100" height="100">';
                }

                // Tampilkan placeholder jika tidak ada gambar
                return '<img src="' . asset('path/to/placeholder.jpg') . '" width="100" height="100">';
            })
            ->addColumn('status_display', function ($product) {
                return $product->is_displayed
                    ? '<span class="badge bg-success">Ditampilkan</span>'
                    : '<span class="badge bg-secondary">Tidak Ditampilkan</span>';
            })
            ->addColumn('actions', function ($product) {
                $toggleAction = $product->is_displayed ? 'hide' : 'show';
                $toggleText   = $product->is_displayed ? 'Hapus dari Katalog' : 'Tambahkan ke Katalog';
                $toggleClass  = $product->is_displayed ? 'btn-danger' : 'btn-success';

                return '
                    <form method="POST" action="' . route('admin.cms.catalog.toggle-display', $product->id) . '" style="display:inline;">
                        ' . csrf_field() . '
                        <input type="hidden" name="action" value="' . $toggleAction . '">
                        <button type="submit" class="btn btn-sm ' . $toggleClass . '">
                            ' . $toggleText . '
                        </button>
                    </form>
                ';
            })
            ->rawColumns(['image', 'status_display', 'actions'])
            ->addIndexColumn()
            ->toJson();
    }

    /**
     * Memperbarui status produk untuk ditampilkan di katalog.
     *
     * @param   \App\Models\Inventory\Product   $product
     * @param   bool    $isDisplayed
     * @return \App\Models\Inventory\Product|null
     */
    public function updateDisplayStatus(Product $product, $isDisplayed)
    {
        try {
            $product->update(['is_displayed' => $isDisplayed]);
            return $product;
        } catch (\Exception $e) {
            return null;
        }
    }
}
