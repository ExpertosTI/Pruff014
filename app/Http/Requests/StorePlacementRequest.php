<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlacementRequest extends FormRequest
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
            'article_id' => 'required|exists:articles,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0|max:999999.99',
            'location' => 'required|string|max:255',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'article_id.required' => 'El artículo es obligatorio.',
            'article_id.exists' => 'El artículo seleccionado no existe.',
            'name.required' => 'El nombre de la colocación es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede exceder 255 caracteres.',
            'price.required' => 'El precio es obligatorio.',
            'price.numeric' => 'El precio debe ser un número.',
            'price.min' => 'El precio debe ser mayor o igual a 0.',
            'price.max' => 'El precio no puede exceder 999999.99.',
            'location.required' => 'La ubicación es obligatoria.',
            'location.string' => 'La ubicación debe ser una cadena de texto.',
            'location.max' => 'La ubicación no puede exceder 255 caracteres.',
        ];
    }
}