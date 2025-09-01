<?php
// app/Http/Requests/Inventory/StockOpnameRequest.php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request form untuk validasi data saat melakukan stock opname.
 * Data berupa array yang berisi stok fisik dan catatan untuk setiap produk.
 */
class StockOpnameRequest extends FormRequest
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
     * @return array
     */
    public function rules(): array
    {
        return [
            'opname_data' => 'required|array',
            'opname_data.*.physical_stock' => 'required|integer|min:0',
            'opname_data.*.note' => 'nullable|string|max:255',
        ];
    }

    /**
     * Dapatkan pesan error kustom untuk aturan validasi yang ditentukan.
     * @return array
     */
    public function messages(): array
    {
        return [
            'opname_data.required' => 'Data stock opname wajib diisi.',
            'opname_data.array' => 'Format data stock opname tidak valid.',
            'opname_data.*.physical_stock.required' => 'Jumlah stok fisik wajib diisi.',
            'opname_data.*.physical_stock.integer' => 'Jumlah stok fisik harus berupa angka.',
            'opname_data.*.physical_stock.min' => 'Jumlah stok fisik minimal 0.',
            'opname_data.*.note.string' => 'Catatan harus berupa teks.',
            'opname_data.*.note.max' => 'Catatan maksimal 255 karakter.',
        ];
    }
}
