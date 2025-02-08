<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'string|required',
            'email' => 'string|nullable',
            'address' => 'string|nullable',
            'contact' => 'string|required',
            'postal_code' => 'string|nullable',
            'phone' => 'string|required',
            'fax' => 'string|nullable',
            'city' => 'string|nullable',
            'e_order_number' => 'integer|nullable',
            'last_date' => 'date|nullable',
        ];
    }
}
