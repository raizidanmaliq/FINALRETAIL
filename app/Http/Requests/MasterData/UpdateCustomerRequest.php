<?php

namespace App\Http\Requests\MasterData;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $customerId = auth()->guard('customer')->id();

        return [
            'name'     => 'required|string|max:255',
            'phone'    => 'required|string|max:20',
            'email'    => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('customers')->ignore($customerId)
            ],
            'address'  => 'required|string',
            'password' => 'nullable|string|min:8|confirmed',
        ];
    }
}
