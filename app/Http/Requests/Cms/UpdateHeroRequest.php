<?php

namespace App\Http\Requests\Cms;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHeroRequest extends FormRequest
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
            'headline' => 'nullable|string|max:255',
            'subheadline' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ];

        // For creation, 'images' is a required array with max 3 items
        if ($this->isMethod('post')) {
            $rules['images'] = 'required|array|min:1|max:3';
            $rules['images.*'] = 'image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        } else {
            // For update, 'images' is an optional array with max 3 items
            $rules['images'] = 'nullable|array|max:3';
            $rules['images.*'] = 'image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        }

        return $rules;
    }
}
