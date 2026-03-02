<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacturaDetalle extends Model
{
    use HasFactory;

    protected $table = 'facturas_detalle';

    protected $fillable = [
        'factura_id', 'producto_id', 'cantidad', 'precio_unitario', 'importe'
    ];

    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
