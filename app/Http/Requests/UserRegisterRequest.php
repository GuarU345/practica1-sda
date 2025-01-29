<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
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
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6'
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
            'name.required' => 'El nombre es requerido',
            'email.string' => 'El email debe ser un texto válido',
            'email.required' => 'El email es requerido',
            'email.unique' => 'Este correo ya está registrado, intenta con otro',
            'password.required' => 'La contraseña es requerida',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres'
        ];
    }
}
