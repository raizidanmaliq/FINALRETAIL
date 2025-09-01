<?php
// app/Http/Requests/Inventory/StoreProductRequest.php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request form untuk validasi data saat menambah produk baru.
 */
class StoreProductRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan untuk membuat request ini.
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Dapatkan aturan validasi yang berlaku untuk request.
     * Aturan 'required' ditambahkan untuk 'image' dan 'description'.
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku',
            'category_id' => 'required|exists:product_categories,id',
            'unit' => 'required|string|max:50',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'inventory' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Diubah menjadi 'required'
            'description' => 'required|string', // Diubah menjadi 'required'
            'promo_label' => 'nullable|in:Bestseller,Limited Stock,New Arrival,Flash Sale'
        ];
    }
}
