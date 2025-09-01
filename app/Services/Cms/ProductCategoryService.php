<?php

namespace App\Services\Cms;

use App\Helpers\ErrorHandling;
use App\Http\Requests\Cms\ProductCategoryRequest;
use App\Models\Cms\ProductCategory;
use Yajra\DataTables\Facades\DataTables;

class ProductCategoryService
{
    public function store(ProductCategoryRequest $request)
    {
        $request->validated();

        try {
            ProductCategory::create([
                'name' => $request->name,
            ]);
        } catch (\Error $e) {
            ErrorHandling::environmentErrorHandling($e->getMessage());
        }
    }

    public function update(ProductCategoryRequest $request, ProductCategory $productCategory)
    {
        $request->validated();

        try {
            $productCategory->update([
                'name' => $request->name,
            ]);
        } catch (\Error $e) {
            ErrorHandling::environmentErrorHandling($e->getMessage());
        }
    }

    public function destroy(ProductCategory $productCategory)
    {
        try {
            $productCategory->delete();
        } catch (\Error $e) {
            ErrorHandling::environmentErrorHandling($e->getMessage());
        }
    }

    public function data(object $productCategory)
    {
        $array = $productCategory->get(['id', 'name']);

        $data = [];
        $no = 0;

        foreach ($array as $item) {
            $no++;
            $nestedData['no'] = $no;
            $nestedData['name'] = $item->name;
            $nestedData['actions'] = '
                <div class="btn-group">
                    <a href="' . route('admin.cms.product_categories.edit', $item) . '" class="btn btn-outline-warning "><i class="fa fa-edit"></i></a>
                    <a href="' . route('admin.cms.product_categories.destroy', $item) . '" class="btn btn-outline-danger btn-delete"><i class="fa fa-trash"></i></a>
                </div>
            ';

            $data[] = $nestedData;
        }

        return DataTables::of($data)->rawColumns(["actions"])->toJson();
    }
}
