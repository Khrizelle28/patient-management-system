<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'role'         => 'required',
            'first_name'   => 'required',
            'middle_name'  => 'nullable',
            'last_name'    => 'required',
            'suffix'       => 'nullable',
            'license_no'   => 'required',
            'ptr_no'       => 'required',
            'email'        => 'required|email',
            'schedule'     => 'nullable',
            'profile_pic'  => 'nullable'
        ];
    }
}
