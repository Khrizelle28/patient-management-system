<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DiagnosisRequest extends FormRequest
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
            'ob_score'               => 'required',
            'gravida'                => 'required',
            'para'                   => 'required',
            'last_menstrual_period'  => 'required',
            'blood_pressure'         => 'required',
            'weight'                 => 'required',
            'type'                   => 'required',
            'age_of_gestation'       => 'required_if:type,pregnant',
            'fundal_height'          => 'required_if:type,pregnant',
            'fetal_heart_tone'       => 'required_if:type,pregnant',
            'remarks'                => 'array', 
            'remarks.*'              => 'required_if:type,pregnant|string',
            'txtarea_remarks'       => [
                                        Rule::requiredIf(function () {
                                            return $this->type === 'non-pregnant' ||
                                                in_array('others', $this->remarks ?? []);
                                        }),
                                        'string',
                                        'nullable',
                                    ],
        ];
    }
}
