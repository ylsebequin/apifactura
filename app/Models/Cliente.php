<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'apellido',
        'ruc',
        'razon_social',
        'email',
        'fecha_nacimiento',
        'direccion',
        'telefono',
    ];
}
