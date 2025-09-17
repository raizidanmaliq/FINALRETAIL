<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductMasterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $productId = $this->route('product') ? $this->route('product')->id : null;

        return [
            'name'              => 'required|string|max:255',
            'sku'               => [
                'required',
                'string',
                Rule::unique('products', 'sku')->ignore($productId),
            ],
            // Perbaikan ada di sini: category_id sekarang hanya wajib jika new_category_name tidak ada, dan validasinya ditangani di after()
            'category_id'       => 'nullable',
            'new_category_name' => 'nullable|string|max:255|unique:product_categories,name',

            'unit'              => 'required|string|max:50',
            'cost_price'        => 'required|numeric|min:0',
            'selling_price'     => 'required|numeric|min:0|gte:cost_price',
            'description'       => 'required|string',
            'promo_label'       => 'nullable|in:Bestseller,Limited Stock,New Arrival,Flash Sale',
            'gender'            => 'required|in:Pria,Wanita,Unisex',
            'size_details'      => 'nullable|string',
            'size_chart_image'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            'colors'            => 'required|array|min:1|max:5',
            'colors.*'          => 'required|string|distinct',
            'sizes'             => 'required|array|min:1',
            'sizes.*'           => 'required|string|distinct',

            'media'             => 'nullable|array|max:6',
            'media.*'           => 'file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:102400',
            'deleted_media'     => 'nullable|array',
            'deleted_media.*'   => 'nullable|integer',
        ];
    }

    /**
     * Add custom validation logic after the default rules.
     */
    public function after(): array
    {
        return [
            function ($validator) {
                // Pastikan setidaknya ada satu kategori, baik yang sudah ada atau yang baru
                if (empty($this->input('category_id')) && empty($this->input('new_category_name'))) {
                    $validator->errors()->add('category_id', 'Pilih kategori atau masukkan nama kategori baru.');
                }

                // Jika category_id diisi, pastikan itu adalah ID yang valid dari database
                if ($this->input('category_id') && $this->input('category_id') !== 'new') {
                    $categoryExists = \App\Models\Cms\ProductCategory::where('id', $this->input('category_id'))->exists();
                    if (!$categoryExists) {
                        $validator->errors()->add('category_id', 'Kategori yang dipilih tidak valid.');
                    }
                }

                // Pastikan total media (lama dan baru) tidak 0 atau melebihi 6
                $existingMediaCount = $this->product->images()->whereNotIn('id', $this->input('deleted_media', []))->count();
                $newMediaCount = count($this->file('media', []));
                $totalMedia = $existingMediaCount + $newMediaCount;

                if ($totalMedia < 1) {
                    $validator->errors()->add('media', 'Setidaknya satu media produk harus diunggah.');
                }

                if ($totalMedia > 6) {
                    $validator->errors()->add('media', 'Jumlah media produk tidak boleh lebih dari 6.');
                }
            }
        ];
    }

    /**
     * Get the custom validation messages.
     */
    public function messages(): array
    {
        return [
            'selling_price.gte'          => 'Harga jual harus lebih besar atau sama dengan harga modal.',
            'colors.min'                 => 'Minimal harus ada 1 warna.',
            'colors.max'                 => 'Maksimal hanya 5 warna yang diperbolehkan.',
            'sizes.min'                  => 'Minimal harus ada 1 ukuran.',
            'colors.*.distinct'          => 'Warna tidak boleh duplikat. Mohon masukkan warna yang unik.',
            'sizes.*.distinct'           => 'Ukuran tidak boleh duplikat. Mohon masukkan ukuran yang unik.',
            'new_category_name.unique'   => 'Nama kategori sudah ada, silakan gunakan yang sudah ada.',
        ];
    }
}
