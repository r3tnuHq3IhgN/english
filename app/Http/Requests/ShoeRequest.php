<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShoeRequest extends FormRequest
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
        return [
            'name' => 'required',
            'brand' => 'required',
            'size' => 'required',
            'price' => 'required|numeric',
            'description' => 'required',
            'category' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'brand.required' => 'Brand is required',
            'size.required' => 'Size is required',
            'price.required' => 'Price is required',
            'price.numeric' => 'Price must be a number',
            'description.required' => 'Description is required',
            'category.required' => 'Category is required',
        ];
    }
}
