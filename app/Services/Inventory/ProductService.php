<?php

namespace App\Services\Inventory;

use App\Models\Inventory\Product;
use App\Models\Inventory\ProductVariant;
use App\Models\Inventory\ProductImage;
use Yajra\DataTables\Facades\DataTables;
use App\Helpers\ErrorHandling;
use App\Helpers\ImageHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class ProductService
{
    protected $lowStockThreshold = 10;
    protected ImageHelpers $imageHelper;

    public function __construct()
    {
        $this->imageHelper = new ImageHelpers('back_assets/img/products/');
    }

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

    public function updateProduct($request, Product $product): ?Product
    {
        try {
            return DB::transaction(function () use ($request, $product) {
                $data = $request->validated();

                // Update master produk
                $product->update([
                    'name'            => $data['name'],
                    'sku'             => $data['sku'],
                    'category_id'     => $data['category_id'],
                    'unit'            => $data['unit'],
                    'cost_price'      => $data['cost_price'],
                    'selling_price'   => $data['selling_price'],
                    'description'     => $data['description'],
                    'promo_label'     => $data['promo_label'] ?? null,
                    'gender'          => $data['gender'],
                    'size_details'    => $data['size_details'] ?? null,
                ]);

                // Update size chart image kalau ada upload baru
                if ($request->hasFile('size_chart_image')) {
                    $uploadedPath = $this->imageHelper->uploadFile($request->file('size_chart_image'));
                    if ($uploadedPath) {
                        $product->update([
                            'size_chart_image' => str_replace(public_path(), '', $uploadedPath),
                        ]);
                    }
                }

                // Reset dan simpan ulang varian
                $product->variants()->delete();
                foreach ($data['colors'] as $color) {
                    foreach ($data['sizes'] as $size) {
                        ProductVariant::create([
                            'product_id' => $product->id,
                            'color'      => $color,
                            'size'       => $size,
                        ]);
                    }
                }

                // Hapus gambar lama yang ditandai
                if (isset($data['deleted_images'])) {
                    foreach ($data['deleted_images'] as $imageId) {
                        $image = ProductImage::find($imageId);
                        if ($image) {
                            $this->imageHelper->deleteImage(public_path($image->image_path));
                            $image->delete();
                        }
                    }
                }

                // Tambah gambar baru
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
