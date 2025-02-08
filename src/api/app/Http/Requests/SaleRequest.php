<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleRequest extends FormRequest
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
            'saleId' => 'nullable|integer',
            'status' => 'required|integer',
            'total' => 'required|numeric',
            'taxes' => 'required|numeric',
            'discount' => 'required|numeric',
            'netDue' => 'required|numeric',
            'saleItems' => 'required|array',
            'saleItems.*.productId' => 'required|integer',
            'saleItems.*.quantity' => 'required|integer',
            'saleItems.*.price' => 'required|numeric',
            'saleItems.*.tax' => 'nullable|numeric',
            'saleItems.*.total' => 'required|numeric',
        ];
    }
}
