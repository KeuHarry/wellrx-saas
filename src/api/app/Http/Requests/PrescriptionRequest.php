<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrescriptionRequest extends FormRequest
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
            'patientId' => 'required|integer',
            'prescriberId' => 'required|integer',
            'status' => 'required|integer',
            'total' => 'required|numeric',
            'taxes' => 'nullable|numeric',
            'netDue' => 'required|numeric',
            'prescriptionDate' => 'required|string',
            'prescriptionExpDate' => 'required|string',
            'discountPercentage' => 'nullable|numeric',
            'discountFlat' => 'nullable|numeric',
            'insurancePercentage' => 'nullable|numeric',
            'insuranceFlat' => 'nullable|numeric',
            'prescriptionItems' => 'required|array',
            'prescriptionItems.*.productId' => 'required|integer',
            'prescriptionItems.*.quantity' => 'required|integer',
            'prescriptionItems.*.courseDays' => 'nullable|integer',
            'prescriptionItems.*.refillAllowedTimes' => 'nullable|integer',
            'prescriptionItems.*.cost' => 'required|numeric',
            'prescriptionItems.*.tax' => 'nullable|numeric',
            'prescriptionItems.*.fee' => 'nullable|numeric',
            'prescriptionItems.*.total' => 'required|numeric',
            'prescriptionItems.*.labelCodes' => 'nullable|array',
        ];
    }
}
