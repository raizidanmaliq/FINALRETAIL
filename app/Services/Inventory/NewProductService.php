<?php

namespace App\Services\Inventory;

use App\Models\Inventory\Product;
use App\Models\Inventory\StockMutation;
use App\Models\Inventory\ProductVariant;
use App\Models\Inventory\ProductImage;
use App\Models\Cms\ProductCategory;
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

                // ✅ Jika user pilih kategori baru
                if (!empty($data['new_category_name'])) {
                    $newCategory = ProductCategory::firstOrCreate([
                        'name' => $data['new_category_name']
                    ]);
                    $data['category_id'] = $newCategory->id;
                }

                // ❌ Buang supaya tidak nyangkut ke insert
                unset($data['new_category_name']);

                // ✅ Simpan produk utama
                $product = Product::create([
                    'name'          => $data['name'],
                    'sku'           => $data['sku'],
                    'category_id'   => $data['category_id'] ?? null,
                    'unit'          => $data['unit'],
                    'cost_price'    => $data['cost_price'],
                    'selling_price' => $data['selling_price'],
                    'stock'         => $data['inventory'] ?? 0,
                    'description'   => $data['description'],
                    'promo_label'   => $data['promo_label'] ?? null,
                    'gender'        => $data['gender'],
                    'is_displayed'  => 1,
                    'size_details'  => $data['size_details'] ?? null,
                ]);

                // ✅ Upload size chart image
                if ($request->hasFile('size_chart_image')) {
                    $uploadedPath = $this->imageHelper->uploadFile($request->file('size_chart_image'));
                    if ($uploadedPath) {
                        $relativePath = str_replace(public_path(), '', $uploadedPath);
                        $relativePath = str_replace('\\', '/', $relativePath);
                        $product->update(['size_chart_image' => $relativePath]);
                    }
                }

                // ✅ Stok awal → simpan ke mutasi
                if (($data['inventory'] ?? 0) > 0) {
                    StockMutation::create([
                        'product_id' => $product->id,
                        'quantity'   => $data['inventory'],
                        'type'       => 'in',
                        'note'       => 'Stok awal produk baru',
                    ]);
                }

                // ✅ Simpan varian (warna + ukuran)
                foreach ($data['colors'] as $color) {
                    foreach ($data['sizes'] as $size) {
                        ProductVariant::create([
                            'product_id' => $product->id,
                            'color'      => $color,
                            'size'       => $size,
                        ]);
                    }
                }

                // ✅ Upload media (gambar & video)
                if ($request->hasFile('media')) {
                    $mediaFiles = $request->file('media');
                    $imageFiles = [];
                    $videoFiles = [];

                    // Pisahkan file gambar dan video
                    foreach ($mediaFiles as $mediaFile) {
                        if (str_starts_with($mediaFile->getMimeType(), 'video')) {
                            $videoFiles[] = $mediaFile;
                        } else {
                            $imageFiles[] = $mediaFile;
                        }
                    }

                    // Gabungkan file gambar dan video, dengan gambar di depan
                    $sortedFiles = array_merge($imageFiles, $videoFiles);

                    // Proses unggahan sesuai urutan yang telah diatur
                    foreach ($sortedFiles as $mediaFile) {
                        $is_video = str_starts_with($mediaFile->getMimeType(), 'video');
                        $uploadedPath = null;

                        if ($is_video) {
                            $fileName = uniqid('vid_') . '.' . $mediaFile->getClientOriginalExtension();
                            $destinationPath = public_path('back_assets/img/products/');
                            if (!file_exists($destinationPath)) {
                                mkdir($destinationPath, 0777, true);
                            }
                            $mediaFile->move($destinationPath, $fileName);
                            $uploadedPath = str_replace(public_path(), '', $destinationPath . $fileName);
                        } else {
                            $uploadedPath = $this->imageHelper->uploadFile($mediaFile);
                        }

                        if ($uploadedPath) {
                            $relativePath = str_replace(public_path(), '', $uploadedPath);
                            $relativePath = str_replace('\\', '/', $relativePath);
                            $relativePath = ltrim($relativePath, '/');

                            ProductImage::create([
                                'product_id' => $product->id,
                                'image_path' => $relativePath,
                                'is_video'   => $is_video,
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
