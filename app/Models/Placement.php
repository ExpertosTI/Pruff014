<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Placement extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id',
        'name',
        'price',
        'location',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Relación con Article
     * Una colocación pertenece a un artículo
     */
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Relación con Purchase
     * Una colocación puede tener muchas compras
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}