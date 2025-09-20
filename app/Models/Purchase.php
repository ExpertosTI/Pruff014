<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'article_id',
        'placement_id',
        'quantity',
        'unit_price',
        'total_price',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    /**
     * Relación con Client
     * Una compra pertenece a un cliente
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Relación con Article
     * Una compra pertenece a un artículo
     */
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Relación con Placement
     * Una compra pertenece a una colocación
     */
    public function placement()
    {
        return $this->belongsTo(Placement::class);
    }

    /**
     * Mutator para calcular automáticamente el precio total
     */
    public function setQuantityAttribute($value)
    {
        $this->attributes['quantity'] = $value;
        $this->calculateTotalPrice();
    }

    public function setUnitPriceAttribute($value)
    {
        $this->attributes['unit_price'] = $value;
        $this->calculateTotalPrice();
    }

    /**
     * Calcula el precio total basado en cantidad y precio unitario
     */
    private function calculateTotalPrice()
    {
        if (isset($this->attributes['quantity']) && isset($this->attributes['unit_price'])) {
            $this->attributes['total_price'] = $this->attributes['quantity'] * $this->attributes['unit_price'];
        }
    }
}