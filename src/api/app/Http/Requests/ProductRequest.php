<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'label' => 'string|required',
            'isPrescription' => 'boolean|required',
            'isGeneric' => 'boolean|required',
            'genericProductId' => 'integer|nullable',
            'barcode' => 'string|nullable',
            'cipCode' => 'string|nullable',
            'markup' => 'numeric|nullable',
            'feeId' => 'integer|nullable',
            'wholesaleCost' => 'numeric|min:0',
            'retailCost' => 'numeric|min:0',
            'unitCost' => 'numeric|min:0',
            'description' => 'string|nullable',
            'qoh' => 'integer|required',
            'minQuantity' => 'integer|nullable',
            'maxQuantity' => 'integer|nullable',
            'isActive' => 'boolean|required',
            'acqCost' => 'numeric|min:0',
            'shelfId' => 'integer|required',
            'productCategoryId' => 'integer|required',
            'isDecomposable' => 'boolean|required',
            'pkgSize' => 'integer|required',
        ];
    }
}
