<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArticleRequest extends FormRequest
{
    // Actualizar artículos es como rebobinar cassettes: tedioso pero necesario
    public function authorize()
    {
        return true;
    }

    // Las reglas son como el manual del Nintendo: nadie las lee pero están ahí
    public function rules()
    {
        $articleId = $this->route('article');
        
        return [
            'barcode' => 'sometimes|required|string|max:255|unique:articles,barcode,' . $articleId,
            'description' => 'sometimes|required|string|max:500',
            'manufacturer' => 'sometimes|nullable|string|max:255',
        ];
    }

    // Los mensajes de error son como los comerciales de TV: repetitivos pero informativos
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
            'manufacturer.string' => 'El fabricante debe ser una cadena de texto.',
            'manufacturer.max' => 'El fabricante no puede exceder 255 caracteres.',
        ];
    }
}
