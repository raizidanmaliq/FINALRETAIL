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
            'name'          => 'required|string|max:255',
            'sku'           => 'required|string|unique:products,sku,' . $productId,
            'category_id'   => 'required|exists:product_categories,id',
            'unit'          => 'required|string|max:50',
            'cost_price'    => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description'   => 'nullable|string',
            'promo_label'   => 'nullable|string|max:255',
        ];
    }
}
