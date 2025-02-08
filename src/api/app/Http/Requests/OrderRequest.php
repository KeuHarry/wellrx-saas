<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'invoiceCode' => 'required|string',
            'supplierId' => 'required|integer',
            'invoiceDate' => 'required|string',
            'status' => 'required|integer|in:1,2',
            'subTotal' => 'required|numeric',
            'taxTotal' => 'required|numeric',
            'total' => 'required|numeric',
            'charges' => 'required|numeric',
            'comment' => 'nullable|string',
            'orderItems' => 'required|array',
            'orderItems.*.productId' => 'required|integer',
            'orderItems.*.quantity' => 'required|integer',
            'orderItems.*.expiryDate' => 'required|string',
            'orderItems.*.price' => 'required|numeric',
            'orderItems.*.discount' => 'nullable|numeric',
            'orderItems.*.tax' => 'nullable|numeric',
            'orderItems.*.wholesaleCost' => 'nullable|numeric|min:0',
            'orderItems.*.retailCost' => 'nullable|numeric|min:0',
            'orderItems.*.markup' => 'nullable|numeric',
        ];
    }

    public function messages()
    {
        return [
            'status.in' => 'The status must be either 1 (In progress) or 2 (Completed).',
        ];
    }
}
