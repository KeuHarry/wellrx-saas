<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
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
            'pharmacyName' => 'required|string',
            'registrationNumber' => 'required|string',
            'account' => 'required|string',
            'pharmacyAddress' => 'required|string',
            'pharmacyCountry' => 'required|string',
            'email' => 'email:rfc,dns',
            'phoneNumber' => 'required|string',
            'password' => 'required|min:8'
        ];
    }
}
