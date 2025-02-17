<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PartialPaymentRequest extends FormRequest
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
            'saleId' => 'required|integer',
            'paymentMethod' => 'required|integer',
            'reference' => 'nullable|string',
            'tendered' => 'required|numeric',
            'amount' => 'required|numeric',
            'change' => 'required|numeric',
        ];
    }
}
