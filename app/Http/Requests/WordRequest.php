<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WordRequest extends FormRequest
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
            //
            'eng' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'vi' => 'required'|'string'|'max:255',

        ];
    }

    public function messages()
    {
        return [
            'eng.required' => 'Trường English không được để trống',
            'type.required' => 'Trường Type không được để trống',
            'vi.required' => 'Trường Tiếng Việt không được để trống',
        ];
    }
}
