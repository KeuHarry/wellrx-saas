<?php

namespace App\Http\Requests\LabelCode;

use Illuminate\Foundation\Http\FormRequest;

class LabelCodeRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'code' => 'required|max:225',
            'label' => 'required|string'
        ];
    }
}
