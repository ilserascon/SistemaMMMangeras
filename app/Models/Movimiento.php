<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo', 'bodega_origen_id', 'bodega_destino_id', 'user_id', 'venta_id', 'factura_id', 'cancelado', 'fecha'
    ];

    public function movimientoDetalles()
    {
        return $this->hasMany(MovimientoDetalle::class, 'movimiento_id', 'id');
    }

    public function bodegaOrigen()
    {
        return $this->belongsTo(Bodega::class, 'bodega_origen_id');
    }

    public function bodegaDestino()
    {
        return $this->belongsTo(Bodega::class, 'bodega_destino_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }
}
