<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OneTimePaymentRequest extends FormRequest
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
            'customerName' => 'nullable|string',
            'paymentMethod' => 'required|integer',
            'isOneTimePayment' => 'required|boolean',
            'paymentReference' => 'nullable|string',
            'tendered' => 'required|numeric',
        ];
    }
}
