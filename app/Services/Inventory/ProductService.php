<?php

namespace App\Services\Inventory;

use App\Models\Inventory\Product;
use App\Models\Inventory\ProductVariant;
use App\Models\Inventory\ProductImage;
use App\Models\Cms\ProductCategory;
use App\Models\Inventory\StockMutation;
use Yajra\DataTables\Facades\DataTables;
use App\Helpers\ErrorHandling;
use App\Helpers\ImageHelpers;
use App\Http\Requests\Inventory\UpdateProductMasterRequest;
use App\Http\Requests\Inventory\StoreProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class ProductService
{
    /**
     * @var int The stock threshold for "low stock" status.
     */
    protected int $lowStockThreshold = 10;

    /**
     * @var ImageHelpers
     */
    protected ImageHelpers $imageHelper;

    public function __construct()
    {
        $this->imageHelper = new ImageHelpers('back_assets/img/products/');
    }

    /**
     * Prepares product data for DataTable display.
     *
     * @param Request $request
     * @return \Yajra\DataTables\DataTables
     */
    public function data(Request $request)
    {
        $query = Product::with('category');

        if ($request->has('filter_stock') && $request->filter_stock == 'low_stock') {
            $query->where('stock', '<=', $this->lowStockThreshold);
        } elseif ($request->has('filter_stock') && $request->filter_stock == 'out_of_stock') {
            $query->where('stock', 0);
        }

        if ($request->has('filter_category') && $request->filter_category != '') {
            $query->where('category_id', $request->filter_category);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('cost_price', fn($product) => 'Rp' . number_format($product->cost_price, 0, ',', '.'))
            ->addColumn('selling_price', fn($product) => 'Rp' . number_format($product->selling_price, 0, ',', '.'))
            ->addColumn('gender', fn($product) => $product->gender ?? '-')
            ->addColumn('variants_count', fn($product) => $product->variants()->count())
            ->addColumn('actions', function ($product) {
                return '
                    <div class="btn-group">
                        <a href="' . route('admin.inventory.products.edit', $product) . '" class="btn btn-outline-warning btn-sm"><i class="fa fa-edit"></i></a>
                        <button class="btn btn-outline-primary btn-sm btn-add-stock" data-id="' . $product->id . '"><i class="fa fa-plus"></i></button>
                        <button class="btn btn-outline-info btn-sm btn-correct-stock" data-id="' . $product->id . '"><i class="fa fa-sync-alt"></i></button>
                    </div>
                ';
            })
            ->rawColumns(['status', 'actions'])
            ->make(true);
    }

    /**
     * Stores a new product and its related data.
     *
     * @param StoreProductRequest $request
     * @return Product|null
     */
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
                    'name' => $data['name'],
                    'sku' => $data['sku'],
                    'category_id' => $data['category_id'] ?? null,
                    'unit' => $data['unit'],
                    'cost_price' => $data['cost_price'],
                    'selling_price' => $data['selling_price'],
                    'stock' => $data['inventory'] ?? 0,
                    'description' => $data['description'],
                    'promo_label' => $data['promo_label'] ?? null,
                    'gender' => $data['gender'],
                    'is_displayed' => 1,
                    'size_details' => $data['size_details'] ?? null,
                ]);

                // ✅ Upload size chart image
                if ($request->hasFile('size_chart_image')) {
                    $uploadedPath = $this->imageHelper->uploadFile($request->file('size_chart_image'));
                    if ($uploadedPath) {
                        $relativePath = str_replace(public_path(), '', $uploadedPath);
                        $relativePath = str_replace('\\', '/', $relativePath);
                        $relativePath = ltrim($relativePath, '/');
                        $product->update(['size_chart_image' => $relativePath]);
                    }
                }

                // ✅ Stok awal → simpan ke mutasi
                if (($data['inventory'] ?? 0) > 0) {
                    StockMutation::create([
                        'product_id' => $product->id,
                        'quantity' => $data['inventory'],
                        'type' => 'in',
                        'note' => 'Stok awal produk baru',
                    ]);
                }

                // ✅ Simpan varian (warna + ukuran)
                foreach ($data['colors'] as $color) {
                    foreach ($data['sizes'] as $size) {
                        ProductVariant::create([
                            'product_id' => $product->id,
                            'color' => $color,
                            'size' => $size,
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
                                'is_video' => $is_video,
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

    /**
     * Updates an existing product and its related data.
     *
     * @param UpdateProductMasterRequest $request
     * @param Product $product
     * @return Product|null
     */
    public function updateProduct(UpdateProductMasterRequest $request, Product $product): ?Product
    {
        try {
            return DB::transaction(function () use ($request, $product) {
                $data = $request->validated();

                // Cek dan buat kategori baru jika ada
                if (isset($data['new_category_name']) && !empty($data['new_category_name'])) {
                    $newCategory = ProductCategory::firstOrCreate([
                        'name' => $data['new_category_name']
                    ]);
                    $data['category_id'] = $newCategory->id;
                }

                // Update master produk
                $product->update([
                    'name' => $data['name'],
                    'sku' => $data['sku'],
                    'category_id' => $data['category_id'],
                    'unit' => $data['unit'],
                    'cost_price' => $data['cost_price'],
                    'selling_price' => $data['selling_price'],
                    'description' => $data['description'],
                    'promo_label' => $data['promo_label'] ?? null,
                    'gender' => $data['gender'],
                    'size_details' => $data['size_details'] ?? null,
                ]);

                // Update size chart image kalau ada upload baru
                if ($request->hasFile('size_chart_image')) {
                    if ($product->size_chart_image) {
                        $this->imageHelper->deleteImage(public_path($product->size_chart_image));
                    }
                    $uploadedPath = $this->imageHelper->uploadFile($request->file('size_chart_image'));
                    if ($uploadedPath) {
                        $product->update(['size_chart_image' => str_replace(public_path(), '', $uploadedPath)]);
                    }
                }

                // Reset dan simpan ulang varian
                $product->variants()->delete();
                foreach ($data['colors'] as $color) {
                    foreach ($data['sizes'] as $size) {
                        ProductVariant::create([
                            'product_id' => $product->id,
                            'color' => $color,
                            'size' => $size,
                        ]);
                    }
                }

                // Hapus media lama yang ditandai oleh user
                if (isset($data['deleted_media']) && is_array($data['deleted_media'])) {
                    foreach ($data['deleted_media'] as $mediaId) {
                        $media = ProductImage::find($mediaId);
                        if ($media) {
                            $this->imageHelper->deleteImage(public_path($media->image_path));
                            $media->delete();
                        }
                    }
                }

                // Tambah media baru
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
                                'is_video' => $is_video,
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
