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
            'name'              => 'required|string|max:255',
            'sku'               => 'required|string|unique:products,sku',

            // ✅ kategori lama / baru
            'category_id'       => 'nullable|exists:product_categories,id',
'new_category_name' => 'nullable|required_if:category_id,new|string|max:255|unique:product_categories,name',


            'unit'              => 'required|string|max:50',
            'cost_price'        => 'required|numeric|min:0',
            'selling_price'     => 'required|numeric|min:0',
            'inventory'         => 'required|integer|min:0',
            'description'       => 'required|string',
            'gender'            => 'required|in:Pria,Wanita,Unisex',

            // Variasi produk
            'colors'            => 'required|array|min:1|max:5',
            'colors.*'          => 'required|string|distinct',
            'sizes'             => 'required|array|min:1',
            'sizes.*'           => 'required|string|distinct',

            // Media
            'media'             => 'required|array|min:1|max:6',
            'media.*'           => 'required|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:102400',

            // Opsional
            'promo_label'       => 'nullable|in:Bestseller,Limited Stock,New Arrival,Flash Sale',
            'size_details'      => 'nullable|string',
            'size_chart_image'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    protected function prepareForValidation()
    {
        // 🚀 FIX: jika pilih "Tambah Kategori Baru"
        if ($this->category_id === 'new') {
            $this->merge([
                'category_id' => null,
            ]);
        }
    }

    public function messages(): array
    {
        return [
            'new_category_name.required_without' => 'Nama kategori atau pemilihan kategori wajib diisi.',
            'new_category_name.unique'           => 'Nama kategori sudah ada, silakan gunakan yang sudah ada.',
            'category_id.exists'                 => 'Kategori lama yang dipilih tidak valid.',
        ];
    }
}
