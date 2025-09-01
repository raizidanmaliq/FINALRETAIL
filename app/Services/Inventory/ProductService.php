<?php

namespace App\Services\Inventory;

use App\Models\Inventory\Product;
use App\Models\Inventory\StockMutation;
use Yajra\DataTables\Facades\DataTables;
use App\Helpers\ErrorHandling;
use Illuminate\Http\Request;

class ProductService
{
    protected $lowStockThreshold = 10;

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
            ->addColumn('cost_price', function ($product) {
                return 'Rp' . number_format($product->cost_price, 0, ',', '.');
            })
            ->addColumn('selling_price', function ($product) {
                return 'Rp' . number_format($product->selling_price, 0, ',', '.');
            })
            ->addColumn('status', function ($product) {
                $status = $product->status == 'active' ? 'Aktif' : 'Tidak Aktif';
                $badgeClass = $product->status == 'active' ? 'bg-success' : 'bg-danger';
                return '<span class="badge ' . $badgeClass . '">' . $status . '</span>';
            })
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

    public function updateProductMasterData($request, Product $product)
    {
        try {
            $data = $request->validated();
            $product->update($data);

            return $product;
        } catch (\Exception $e) {
            ErrorHandling::environmentErrorHandling($e->getMessage());
            return false;
        }
    }
}
