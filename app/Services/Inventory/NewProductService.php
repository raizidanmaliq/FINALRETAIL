<?php
// app/Services/Inventory/NewProductService.php

namespace App\Services\Inventory;

use App\Models\Inventory\Product;
use App\Models\Inventory\StockMutation;
use App\Http\Requests\Inventory\StoreProductRequest;
use App\Helpers\ImageHelpers;
use App\Helpers\ErrorHandling;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * Service untuk menangani logika bisnis terkait penambahan produk baru.
 */
class NewProductService
{
    protected ImageHelpers $imageHelper;

    public function __construct()
    {
        // Path ini harus sama dengan yang akan diakses dari public
        $this->imageHelper = new ImageHelpers('back_assets/img/products/');
    }

    /**
     * Menyimpan produk baru dan mencatat mutasi stok awal.
     * @param StoreProductRequest $request
     * @return Product|null
     */
    public function store(StoreProductRequest $request): ?Product
    {
        try {
            return DB::transaction(function () use ($request) {
                $data = $request->validated();

                $imagePath = 'noimage.png';

                // Panggil helper untuk mengunggah gambar
                // Helper Anda mengembalikan path absolut, jadi kita tangkap nilai tersebut.
                $uploadedPath = $this->imageHelper->uploadImage($request, 'image');

                if ($uploadedPath != 'noimage.png') {
                    // Konversi path absolut menjadi path relatif yang bisa disimpan di database
                    // Kita perlu memotong bagian path_absolut_server/public dari string yang dikembalikan helper.
                    // Asumsi public_path() adalah root dari folder public.
                    $imagePath = str_replace(public_path(), '', $uploadedPath);
                }

                $data['image'] = $imagePath;

                // Ganti nama kunci 'inventory' menjadi 'stock' untuk model Product
                $stock = $data['inventory'] ?? 0;
                $data['stock'] = $stock;
                unset($data['inventory']);

                $data['is_displayed'] = 1;

                // Langkah 1: Buat produk baru di database
                $product = Product::create($data);

                // Langkah 2: Buat catatan mutasi stok untuk stok awal jika stok > 0
                if ($stock > 0) {
                    StockMutation::create([
                        'product_id' => $product->id,
                        'quantity' => $stock,
                        'type' => 'in',
                        'note' => 'Stok awal produk baru',
                    ]);
                }

                return $product;
            });
        } catch (Exception $e) {
            ErrorHandling::environmentErrorHandling($e->getMessage());
            return null;
        }
    }
}
