<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    // Los clientes son como los Tamagotchis: hay que cuidarlos o se mueren
    public function authorize()
    {
        return true;
    }

    // Validar clientes es como sintonizar la radio AM: requiere paciencia
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'client_type' => 'required|string|in:regular,premium',
        ];
    }

    // Los errores de cliente son como las llamadas perdidas: hay que devolverlas
    public function messages()
    {
        return [
            'name.required' => 'El nombre del cliente es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede exceder 255 caracteres.',
            'phone_number.required' => 'El número de teléfono es obligatorio.',
            'phone_number.string' => 'El número de teléfono debe ser una cadena de texto.',
            'phone_number.max' => 'El número de teléfono no puede exceder 20 caracteres.',
            'client_type.required' => 'El tipo de cliente es obligatorio.',
            'client_type.in' => 'El tipo de cliente debe ser: regular o premium.',
        ];
    }
}
