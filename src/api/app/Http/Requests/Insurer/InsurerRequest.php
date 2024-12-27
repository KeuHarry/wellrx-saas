<?php

namespace App\Http\Requests\Insurer;

use Illuminate\Foundation\Http\FormRequest;

class InsurerRequest extends FormRequest
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
            'name' => 'required|string',
            'taxPayerNumber' => 'required|string',
            'registrationNumber' => 'required|string',
            'creditLimit' => 'numeric|min:0',
            'balance' => 'numeric|min:0',
            'deposit' => 'numeric|min:0'
        ];
    }
}
