<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductMasterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $productId = $this->route('product') ? $this->route('product')->id : null;

        return [
            // ✅ Data utama produk
            'name'            => 'required|string|max:255',
            'sku'             => 'required|string|unique:products,sku,' . $productId,
            'category_id'     => 'required|exists:product_categories,id',
            'unit'            => 'required|string|max:50',
            'cost_price'      => 'required|numeric|min:0',
            'selling_price'   => 'required|numeric|min:0|gte:cost_price',
            'description'     => 'required|string',
            'promo_label'     => 'nullable|in:Bestseller,Limited Stock,New Arrival,Flash Sale',
            'gender'          => 'required|in:Pria,Wanita,Unisex',

            // ✅ Tambahan field baru
            'size_details'    => 'nullable|string',
            'size_chart_image'=> 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            // ✅ Colors (minimal 1, maksimal 20, semua unik)
            'colors'          => 'required|array|min:1|max:20',
            'colors.*'        => 'required|string|distinct',

            // ✅ Sizes (minimal 1, maksimal 20, semua unik)
            'sizes'           => 'required|array|min:1|max:20',
            'sizes.*'         => 'required|string|distinct',

            // ✅ Images
            'images.*'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'deleted_images'  => 'nullable|array',
            'deleted_images.*'=> 'nullable|integer',
        ];
    }

    public function messages()
    {
        return [
            'selling_price.gte'   => 'Harga jual harus lebih besar atau sama dengan harga modal.',
            'colors.min'          => 'Minimal harus ada 1 warna.',
            'sizes.min'           => 'Minimal harus ada 1 ukuran.',
            'colors.*.distinct'   => 'Warna tidak boleh duplikat. Mohon masukkan warna yang unik.',
            'sizes.*.distinct'    => 'Ukuran tidak boleh duplikat. Mohon masukkan ukuran yang unik.',
        ];
    }
}
