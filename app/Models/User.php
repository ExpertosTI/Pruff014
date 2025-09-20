<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Los campos rellenables son como los espacios en blanco de Mad Libs: hay que llenarlos bien
    protected $fillable = [
        'name',
        'email',
        'password',
        'cedula',
        'phone_number',
        'blood_type',
    ];

    // Ocultar campos es como usar corrector líquido: nadie debe ver lo que hay debajo
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Los casts son como los adaptadores de cassette para el auto: convierten formatos
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
