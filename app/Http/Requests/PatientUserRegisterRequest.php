<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientUserRegisterRequest extends FormRequest
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
            'first_name'          => 'required',
            'middle_name'         => 'nullable',
            'last_name'           => 'required',
            'age'                 => 'required',
            'civil_status'        => 'required',
            'street'              => 'nullable',
            'barangay'            => 'required',
            'city_municipality'   => 'required',
            'province'            => 'required',
            'occupation'          => 'required',
            'contact_no'          => 'required',
            'birthday'            => 'required',
            'birthplace'          => 'required',
        ];
    }
}
