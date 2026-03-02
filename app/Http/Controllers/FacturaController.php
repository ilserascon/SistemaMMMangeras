<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\FacturaDetalle;
use App\Models\Producto;
use App\Models\Inventario;
use App\Models\Movimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacturaController extends Controller
{
    public function index()
    {
        $facturas = Factura::with('user')->latest()->paginate(10);
        return view('facturas.index', compact('facturas'));
    }

    public function createManual()
    {
        $productos = Producto::all();
        return view('facturas.create_manual', compact('productos'));
    }

    public function storeManual(Request $request)
    {
        $request->validate([
            'productos' => 'required|array|min:1'
        ], [
            'productos.required' => 'Debe registrar al menos un producto.',
            'productos.min' => 'Debe registrar al menos un producto.'
        ]);

        DB::transaction(function () use ($request) {

            $factura = Factura::create([
                'folio' => $request->folio,
                'proveedor' => $request->proveedor,
                'fecha' => $request->fecha,
                'user_id' => auth()->id()
            ]);
            
            $movimiento = Movimiento::create([
                'tipo' => 'Entrada',
                'bodega_destino_id' => $request->bodega_id,
                'user_id' => auth()->id(),
                'factura_id' => $factura->id,
                'fecha' => now()
            ]);
            
            foreach ($request->productos as $p) {
                // Buscar producto por código
                $producto = Producto::firstOrCreate(
                    ['codigo' => $p['codigo']], // si existe toma id
                    [
                        'descripcion' => $p['descripcion'], 
                        'unidad' => $p['unidad'], 
                        'cprodserv' => $p['cprodserv']
                    ]
                );
            
                FacturaDetalle::create([
                    'factura_id' => $factura->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $p['cantidad']
                ]);

                $movimiento->movimientoDetalles()->create([
                    'movimiento_id' => $movimiento->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $p['cantidad']
                ]);

                // Actualizar inventario
                $inventario = Inventario::firstOrCreate([
                    'producto_id' => $producto->id,
                    'bodega_id' => $request->bodega_id
                ]);
                $inventario->increment('cantidad', $p['cantidad']);
            }


        });

        return redirect()->route('facturas.createManual')
            ->with('success','Factura registrada correctamente');
    }
}
