<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserLoginRequest extends FormRequest
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
            'email' => 'required|email',
            'password' => 'required|string'
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
            'email.required' => 'El email es requerido',
            'email.email' => 'El email debe de ser un email valido',
            'password.required' => 'La contraseña es requerida',
        ];
    }
}
