<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    // Los campos asignables son como los canales de TV: solo algunos funcionan bien
    protected $fillable = [
        'name',
        'phone_number',
        'client_type',
    ];

    // Los casts son como cambiar de VHS a Betamax: mismo contenido, diferente formato
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
