<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InventoryRequest extends FormRequest
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
            'productId' => 'integer|required',
            'quantity' => 'numeric|min:1',
            'type' => 'integer|required',
            'expiryDate' => 'date|required',
            'batchId' => 'required|string',
            'acqCost' => 'numeric|min:0',
            'wholesaleCost' => 'numeric|min:0',
            'retailCost' => 'numeric|min:0',
            'unitCost' => 'numeric|min:0',
            'supplierId' => 'integer|required',
            'reason' => 'required|string',
        ];
    }
}
