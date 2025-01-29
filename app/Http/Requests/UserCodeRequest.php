<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCodeRequest extends FormRequest
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
            'code' => 'required|numeric|digits:6'
        ];
    }

    public function messages()
    {
        return [
            'code.required' => 'El codigo es requerido',
            'code.numeric' => 'El codigo debe de ser un numero',
            'code.digits' => 'El codigo debe de ser de 6 numeros'
        ];
    }
}
