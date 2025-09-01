<?php

namespace App\Http\Requests\Cms;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ];

        // Aturan validasi email disesuaikan untuk update dan store
        if ($this->isMethod('post')) {
            // Saat membuat data baru (store), email harus unik
            $rules['email'] = 'required|email|unique:customers,email';
            $rules['password'] = 'required|string|min:8';
        } else {
            // Saat mengupdate data, abaikan email pelanggan saat ini dari validasi unik
            $rules['email'] = ['required', 'email', Rule::unique('customers', 'email')->ignore($this->route('customer'))];
            // Password bisa opsional saat update
            $rules['password'] = 'nullable|string|min:8';
        }

        return $rules;
    }
}
