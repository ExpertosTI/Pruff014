<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchaseRequest extends FormRequest
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
            'client_id' => 'sometimes|exists:clients,id',
            'article_id' => 'sometimes|exists:articles,id',
            'placement_id' => 'sometimes|exists:placements,id',
            'quantity' => 'sometimes|integer|min:1|max:999999',
            'unit_price' => 'sometimes|numeric|min:0|max:999999.99',
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
            'client_id.exists' => 'El cliente seleccionado no existe.',
            'article_id.exists' => 'El artículo seleccionado no existe.',
            'placement_id.exists' => 'La colocación seleccionada no existe.',
            'quantity.integer' => 'La cantidad debe ser un número entero.',
            'quantity.min' => 'La cantidad debe ser al menos 1.',
            'quantity.max' => 'La cantidad no puede exceder 999999.',
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
        // Recalcular el precio total si se actualizan cantidad o precio unitario
        if ($this->has('quantity') || $this->has('unit_price')) {
            $quantity = $this->quantity ?? $this->route('purchase')->quantity;
            $unitPrice = $this->unit_price ?? $this->route('purchase')->unit_price;
            
            $this->merge([
                'total_price' => $quantity * $unitPrice
            ]);
        }
    }
}