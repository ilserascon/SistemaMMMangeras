<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\Movimiento;
use App\Models\Producto;
use App\Models\Bodega;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TrasladoController extends Controller
{
    public function index(Request $request)
    {
        $bodegas = Bodega::all();

        $traslados = Movimiento::with(['bodegaOrigen', 'bodegaDestino', 'user'])
            ->where('tipo', 'Traslado')
            ->when($request->bodega_origen_id, fn($q) =>
                $q->where('bodega_origen_id', $request->bodega_origen_id))
            ->when($request->bodega_destino_id, fn($q) =>
                $q->where('bodega_destino_id', $request->bodega_destino_id))
            ->when($request->fecha_inicio, fn($q) =>
                $q->whereDate('created_at', '>=', $request->fecha_inicio))
            ->when($request->fecha_fin, fn($q) =>
                $q->whereDate('created_at', '<=', $request->fecha_fin))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('traslados.index', compact('traslados', 'bodegas'));
    }

    public function create()
    {
        $bodegas = Bodega::where('inhabilitado', 0)->get();

        return view('traslados.create', compact('bodegas'));
    }

    public function store(Request $request)
    {
        try {
            if ($request->bodega_origen_id == $request->bodega_destino_id) {
                throw new \Exception('La bodega de origen y destino no pueden ser la misma.');
            }

             $request->validate([
                'bodega_origen_id' => 'required|different:bodega_destino_id',
                'bodega_destino_id' => 'required',
                'productos' => 'required|array|min:1',
                'productos.*.producto_id' => 'required|exists:productos,id',
                'productos.*.cantidad' => 'required|numeric|min:0.01'
            ]);

             $movimiento = null;

             DB::transaction(function () use ($request, &$movimiento) {

                $movimiento = Movimiento::create([
                    'tipo' => 'Traslado',
                    'bodega_origen_id' => $request->bodega_origen_id,
                    'bodega_destino_id' => $request->bodega_destino_id,
                    'fecha' => now(),
                    'user_id' => auth()->id()
                ]);

                foreach ($request->productos as $p) {

                    $productoId = $p['producto_id'];
                    $cantidad = $p['cantidad'];

                    //INVENTARIO ORIGEN
                    $invOrigen = Inventario::where('producto_id', $productoId)
                        ->where('bodega_id', $request->bodega_origen_id)
                        ->lockForUpdate()
                        ->first();

                    if (!$invOrigen || $invOrigen->cantidad < $cantidad) {
                        throw new \Exception("Stock insuficiente en bodega origen.");
                    }

                    $invOrigen->decrement('cantidad', $cantidad);

                    //INVENTARIO DESTINO
                    $invDestino = Inventario::firstOrCreate(
                        [
                            'producto_id' => $productoId,
                            'bodega_id' => $request->bodega_destino_id
                        ],
                        ['cantidad' => 0]
                    );

                    $invDestino->increment('cantidad', $cantidad);

                    $movimiento->movimientoDetalles()->create([
                        'producto_id' => $productoId,
                        'cantidad' => $cantidad
                    ]);
                }
            });

            return redirect()->route('traslados.show', $movimiento->id)->with('success', 'Traslado realizado correctamente.');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $traslado = Movimiento::with([
            'movimientoDetalles.producto',
            'bodegaOrigen',
            'bodegaDestino',
            'user'
        ])->findOrFail($id);

        return view('traslados.show', compact('traslado'));
    }
}
