<?php

namespace App\Http\Requests\PurchaseOrder;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseOrderRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Changed from 'nullable' to 'required'
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'order_date' => ['required', 'date'],

            // Changed from 'nullable' to 'required'
            'arrival_estimate_date' => ['required', 'date'],

            'status' => ['nullable', 'string', 'in:pending,on_delivery,completed'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.product_id' => ['required', 'exists:products,id'],
            'products.*.quantity' => ['required', 'integer', 'min:1'],
            'products.*.unit_price' => ['required', 'numeric', 'min:0.01'],
        ];
    }
}
