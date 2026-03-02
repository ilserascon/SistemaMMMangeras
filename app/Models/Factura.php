<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;

    protected $fillable = [
        'folio', 'proveedor', 'fecha', 'subtotal', 'iva', 'total', 'bodega_id', 'user_id', 'cancelado'
    ];

    // Relación con detalles
    public function detalles()
    {
        return $this->hasMany(FacturaDetalle::class, 'factura_id');
    }

    // Relación con bodega
    public function bodega()
    {
        return $this->belongsTo(Bodega::class);
    }

    // Relación con movimiento
    public function movimiento()
    {
        return $this->hasMany(Movimiento::class, 'factura_id');
    }

    // Relación con usuario    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
