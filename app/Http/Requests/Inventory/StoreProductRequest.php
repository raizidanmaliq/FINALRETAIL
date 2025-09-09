<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

   public function rules(): array
{
    return [
        'name'            => 'required|string|max:255',
        'sku'             => 'required|string|unique:products,sku',
        'category_id'     => 'required|exists:product_categories,id',
        'unit'            => 'required|string|max:50',
        'cost_price'      => 'required|numeric|min:0',
        'selling_price'   => 'required|numeric|min:0',
        'inventory'       => 'required|integer|min:0',
        'description'     => 'required|string',
        'gender'          => 'required|in:Pria,Wanita,Unisex',

        // ✅ Variasi produk (WAJIB)
        'colors'          => 'required|array|min:1',
        'colors.*'        => 'required|string|distinct',
        'sizes'           => 'required|array|min:1',
        'sizes.*'         => 'required|string|distinct',

        // ✅ Foto produk utama (WAJIB)
        'images'          => 'required|array|min:1',
        'images.*'        => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',

        // ❌ Pengecualian (boleh kosong)
        'promo_label'     => 'nullable|in:Bestseller,Limited Stock,New Arrival,Flash Sale',
        'size_details'    => 'nullable|string',
        'size_chart_image'=> 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ];
}
}
