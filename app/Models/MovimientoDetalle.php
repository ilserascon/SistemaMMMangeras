<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoDetalle extends Model
{
    use HasFactory;

    protected $table = 'movimientos_detalle';

    protected $fillable = [
        'movimiento_id', 'producto_id', 'cantidad'
    ];

    public function movimiento()
    {
        return $this->belongsTo(Movimiento::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
