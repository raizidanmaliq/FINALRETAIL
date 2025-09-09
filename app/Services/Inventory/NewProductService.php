<?php

namespace App\Services\Inventory;

use App\Models\Inventory\Product;
use App\Models\Inventory\StockMutation;
use App\Models\Inventory\ProductVariant;
use App\Models\Inventory\ProductImage;
use App\Http\Requests\Inventory\StoreProductRequest;
use App\Helpers\ImageHelpers;
use App\Helpers\ErrorHandling;
use Illuminate\Support\Facades\DB;
use Exception;

class NewProductService
{
    protected ImageHelpers $imageHelper;

    public function __construct()
    {
        $this->imageHelper = new ImageHelpers('back_assets/img/products/');
    }

    public function store(StoreProductRequest $request): ?Product
    {
        try {
            return DB::transaction(function () use ($request) {
                $data = $request->validated();

                // Buat produk utama
                $product = Product::create([
                    'name'            => $data['name'],
                    'sku'             => $data['sku'],
                    'category_id'     => $data['category_id'],
                    'unit'            => $data['unit'],
                    'cost_price'      => $data['cost_price'],
                    'selling_price'   => $data['selling_price'],
                    'stock'           => $data['inventory'] ?? 0,
                    'description'     => $data['description'],
                    'promo_label'     => $data['promo_label'] ?? null,
                    'gender'          => $data['gender'], // masih dipakai
                    'is_displayed'    => 1,
                    'size_details'    => $data['size_details'] ?? null,
                ]);

                // Upload size chart image kalau ada
                if ($request->hasFile('size_chart_image')) {
                    $uploadedPath = $this->imageHelper->uploadFile($request->file('size_chart_image'));
                    if ($uploadedPath) {
                        $product->update([
                            'size_chart_image' => str_replace(public_path(), '', $uploadedPath),
                        ]);
                    }
                }

                // Stok awal -> simpan ke mutasi
                if (($data['inventory'] ?? 0) > 0) {
                    StockMutation::create([
                        'product_id' => $product->id,
                        'quantity'   => $data['inventory'],
                        'type'       => 'in',
                        'note'       => 'Stok awal produk baru',
                    ]);
                }

                // Simpan varian produk dari kombinasi warna dan ukuran
                foreach ($data['colors'] as $color) {
                    foreach ($data['sizes'] as $size) {
                        ProductVariant::create([
                            'product_id' => $product->id,
                            'color'      => $color,
                            'size'       => $size,
                        ]);
                    }
                }

                // Simpan banyak gambar produk
                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $imageFile) {
                        $uploadedPath = $this->imageHelper->uploadFile($imageFile);
                        if ($uploadedPath) {
                            ProductImage::create([
                                'product_id' => $product->id,
                                'image_path' => str_replace(public_path(), '', $uploadedPath),
                            ]);
                        }
                    }
                }

                return $product;
            });
        } catch (Exception $e) {
            ErrorHandling::environmentErrorHandling($e->getMessage());
            return null;
        }
    }
}
