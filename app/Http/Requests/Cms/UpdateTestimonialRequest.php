<?php

namespace App\Http\Requests\Cms;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTestimonialRequest extends FormRequest
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
            'customer_name' => 'required|string|max:255',
            'review' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'order_date' => 'nullable|date',
            'product_name' => 'nullable|string|max:255',
        ];

        // The logic for 'customer_photo' is fine as is
        if ($this->isMethod('post')) {
            $rules['customer_photo'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        } else {
            $rules['customer_photo'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'customer_name.required' => 'Nama Pelanggan wajib diisi.',
            'review.required' => 'Ulasan wajib diisi.',
            'rating.required' => 'Rating wajib diisi.',
            'rating.min' => 'Rating harus minimal :min.',
            'rating.max' => 'Rating tidak boleh lebih dari :max.',
            'customer_photo.image' => 'File harus berupa gambar.',
        ];
    }
}
