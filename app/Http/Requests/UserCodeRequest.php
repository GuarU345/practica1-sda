<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCodeRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta solicitud.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Obtiene las reglas de validación que se aplican a la solicitud.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'code' => 'required|numeric|digits:6'
        ];
    }

    /**
     * Define los mensajes de error personalizados para las reglas de validación.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'code.required' => 'El codigo es requerido',
            'code.numeric' => 'El codigo debe de ser un numero',
            'code.digits' => 'El codigo debe de ser de 6 numeros'
        ];
    }
}
