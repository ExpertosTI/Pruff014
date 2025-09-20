<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
{
    // Actualizar clientes es como cambiar pilas al walkman: delicado pero necesario
    public function authorize()
    {
        return true;
    }

    // Las reglas de actualización son como el dial-up: lentas pero efectivas
    public function rules()
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'phone_number' => 'sometimes|nullable|string|max:20',
            'client_type' => 'sometimes|string|in:regular,premium',
        ];
    }

    // Los mensajes son como los fax: llegan tarde pero llegan
    public function messages()
    {
        return [
            'name.required' => 'El nombre del cliente es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede exceder 255 caracteres.',
            'phone_number.string' => 'El número de teléfono debe ser una cadena de texto.',
            'phone_number.max' => 'El número de teléfono no puede exceder 20 caracteres.',
            'client_type.in' => 'El tipo de cliente debe ser: regular o premium.',
        ];
    }
}
