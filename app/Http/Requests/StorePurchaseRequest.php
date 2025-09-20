<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseRequest extends FormRequest
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
            'client_id' => 'required|exists:clients,id',
            'article_id' => 'required|exists:articles,id',
            'placement_id' => 'required|exists:placements,id',
            'quantity' => 'required|integer|min:1|max:999999',
            'unit_price' => 'required|numeric|min:0|max:999999.99',
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
            'client_id.required' => 'El cliente es obligatorio.',
            'client_id.exists' => 'El cliente seleccionado no existe.',
            'article_id.required' => 'El artículo es obligatorio.',
            'article_id.exists' => 'El artículo seleccionado no existe.',
            'placement_id.required' => 'La colocación es obligatoria.',
            'placement_id.exists' => 'La colocación seleccionada no existe.',
            'quantity.required' => 'La cantidad es obligatoria.',
            'quantity.integer' => 'La cantidad debe ser un número entero.',
            'quantity.min' => 'La cantidad debe ser al menos 1.',
            'quantity.max' => 'La cantidad no puede exceder 999999.',
            'unit_price.required' => 'El precio unitario es obligatorio.',
            'unit_price.numeric' => 'El precio unitario debe ser un número.',
            'unit_price.min' => 'El precio unitario debe ser mayor o igual a 0.',
            'unit_price.max' => 'El precio unitario no puede exceder 999999.99.',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Calcular el precio total automáticamente
        if ($this->has('quantity') && $this->has('unit_price')) {
            $this->merge([
                'total_price' => $this->quantity * $this->unit_price
            ]);
        }
    }
}