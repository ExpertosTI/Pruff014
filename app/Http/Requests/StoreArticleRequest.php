<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
{
    // Los artículos son como los CDs: hay que cuidar que no se rayen
    public function authorize()
    {
        return true;
    }

    // Validar artículos es como programar el VHS: complicado pero gratificante
    public function rules()
    {
        return [
            'barcode' => 'required|string|max:255|unique:articles',
            'description' => 'required|string|max:500',
            'manufacturer' => 'required|string|max:255',
        ];
    }

    // Los errores son como las cintas enredadas: hay que desenredarlos con paciencia
    public function messages()
    {
        return [
            'barcode.required' => 'El código de barras es obligatorio.',
            'barcode.string' => 'El código de barras debe ser una cadena de texto.',
            'barcode.max' => 'El código de barras no puede exceder 255 caracteres.',
            'barcode.unique' => 'Este código de barras ya está registrado.',
            'description.required' => 'La descripción del artículo es obligatoria.',
            'description.string' => 'La descripción debe ser una cadena de texto.',
            'description.max' => 'La descripción no puede exceder 500 caracteres.',
            'manufacturer.required' => 'El fabricante es obligatorio.',
            'manufacturer.string' => 'El fabricante debe ser una cadena de texto.',
            'manufacturer.max' => 'El fabricante no puede exceder 255 caracteres.',
        ];
    }
}
