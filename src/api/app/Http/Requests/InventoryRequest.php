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
            'inventories' => 'required|array',
            'inventories.*.productId' => 'integer|required',
            'inventories.*.quantity' => 'numeric|min:1',
            'inventories.*.type' => 'integer|required',
            'inventories.*.expiryDate' => 'date|required',
            'inventories.*.acqCost' => 'numeric|min:0',
            'inventories.*.wholesaleCost' => 'numeric|min:0',
            'inventories.*.retailCost' => 'numeric|min:0',
            'inventories.*.unitCost' => 'numeric|min:0',
            'inventories.*.subTotal' => 'numeric|min:0',
            'inventories.*.supplierId' => 'integer|required',
            'inventories.*.reason' => 'nullable|string',
        ];
    }
}
