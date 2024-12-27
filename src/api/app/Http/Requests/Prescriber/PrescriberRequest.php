<?php

namespace App\Http\Requests\Prescriber;

use Illuminate\Foundation\Http\FormRequest;

class PrescriberRequest extends FormRequest
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
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'registrationNumber' => 'required|string',
            'phone' => 'required|string'
        ];
    }
}
