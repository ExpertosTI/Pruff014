<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    // Actualizar es como cambiar de canal: a veces funciona, a veces no
    public function authorize()
    {
        return true;
    }

    // Las reglas de actualización son como el Windows 95: complicadas pero necesarias
    public function rules()
    {
        $userId = $this->route('user');
        
        return [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $userId,
            'password' => 'sometimes|nullable|string|min:8|confirmed',
            'cedula' => 'sometimes|required|string|max:20|unique:users,cedula,' . $userId,
            'phone_number' => 'sometimes|nullable|string|max:20',
            'blood_type' => 'sometimes|nullable|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
        ];
    }

    // Los mensajes de error son como los beepers: molestos pero útiles
    public function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email debe tener un formato válido.',
            'email.unique' => 'Este email ya está registrado.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
            'cedula.required' => 'La cédula es obligatoria.',
            'cedula.unique' => 'Esta cédula ya está registrada.',
            'blood_type.in' => 'El tipo de sangre debe ser uno de: A+, A-, B+, B-, AB+, AB-, O+, O-.',
        ];
    }
}
