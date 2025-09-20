<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    // Los atributos rellenables son como los stickers del álbum: hay espacios específicos para cada uno
    protected $fillable = [
        'barcode',
        'description',
        'manufacturer',
    ];

    // Los casts son como los traductores de Game Boy: convierten el idioma para que entiendas
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con Placement
     * Un artículo puede tener muchas colocaciones
     */
    public function placements()
    {
        return $this->hasMany(Placement::class);
    }

    /**
     * Relación con Purchase
     * Un artículo puede tener muchas compras
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
