<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientRequest extends FormRequest
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
            'dob' => 'required|date',
            'address' => 'string|nullable',
            'city' => 'required|string',
            'email' => 'email:rfc,dns|nullable',
            'phone' => 'required|string',
            'company' => 'string|nullable',
            'insurerId' => 'integer|nullable|prohibited_if:caretakerId,*',
            'caretakerId' => 'integer|nullable|prohibited_if:insurerId,*',
            'intermediaryId' => 'integer|nullable',
            'allergies' => 'string|nullable',
            'medecineHistory' => 'string|nullable',
        ];
    }
}
