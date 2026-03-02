<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'unidad',
        'cprodserv'
    ];

    public function inventarios()
    {
        return $this->hasOne(Inventario::class);
    }

    public function movimientoDetalles()
    {
        return $this->hasMany(MovimientoDetalle::class);
    }
}
