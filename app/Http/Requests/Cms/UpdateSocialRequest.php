<?php

namespace App\Http\Requests\Cms;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSocialRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'button_text' => 'nullable|string|max:255',
            'button_link' => 'nullable|url|max:255',
            'shopee_link' => 'nullable|url|max:255',
            'tokopedia_link' => 'nullable|url|max:255',
            'lazada_link' => 'nullable|url|max:255',
            'is_active' => 'boolean'
        ];

        // Untuk pembuatan, 'images' adalah array wajib dengan min:1 dan max:3
        if ($this->isMethod('post')) {
            $rules['images'] = 'required|array|min:1|max:3';
            $rules['images.*'] = 'image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        } else {
            // Untuk pembaruan, 'images' adalah array opsional dengan max:3
            $rules['images'] = 'nullable|array|max:3';
            $rules['images.*'] = 'image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        }

        return $rules;
    }
}
