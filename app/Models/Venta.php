<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'fecha_venta',
        'total_venta',
        'estado'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
    public function productos()
    {
        return $this->belongsTo(Productos::class, 'venta_detalles')
            ->withPivot('cantidad', 'precio', 'subtotal');
    }
    public function detalles()
    {
        return $this->hasMany(VentaDetalle::class);
    }
}
