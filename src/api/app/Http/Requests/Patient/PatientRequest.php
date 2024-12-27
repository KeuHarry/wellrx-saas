<?php

namespace App\Http\Requests\Patient;

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
            'dob' => 'required',
            'address' => 'required|string',
            'city' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'company' => 'required|string',
            'insurerId' => 'integer|nullable',
            'caretakerId' => 'integer|nullable',
            'allergies' => 'string|nullable',
            'medecineHistory' => 'string|nullable'
        ];
    }
}
