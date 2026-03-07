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
    public function index(Request $request)
    {
        $query = Factura::with('user'); 

        if ($request->filled('folio')) {
            $query->where('folio', 'like', '%' . $request->folio . '%');
        }

        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->whereBetween('fecha', [
                $request->fecha_inicio,
                $request->fecha_fin
            ]);
        } elseif ($request->filled('fecha_inicio')) {
            $query->whereDate('fecha', '>=', $request->fecha_inicio);
        } elseif ($request->filled('fecha_fin')) {
            $query->whereDate('fecha', '<=', $request->fecha_fin);
        }

        $facturas = $query->orderBy('fecha', 'desc')->paginate(10);

        return view('facturas.index', compact('facturas'));
    }

    public function show($id)
    {
        $factura = Factura::with([
            'user',
            'detalles.producto'
        ])->findOrFail($id);

        return view('facturas.show', compact('factura'));
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
                'bodega_destino_id' => 1,
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
                    'bodega_id' => 1
                ]);
                $inventario->increment('cantidad', $p['cantidad']);
            }


        });

        return redirect()->route('facturas.createManual')
            ->with('success','Factura registrada correctamente');
    }

    public function cancelar(Factura $factura)
    {
        try {
            if ($factura->cancelado === 1) {
                return back()->with('error', 'La factura ya está cancelada.');
            }

            DB::transaction(function () use ($factura) {

                foreach ($factura->detalles as $detalle) {

                    $inventario = Inventario::where([
                        'producto_id' => $detalle->producto_id,
                        'bodega_id' => 1
                    ])->lockForUpdate()->first();

                    if (!$inventario) {
                        throw new \Exception("No existe inventario para el producto ID {$detalle->producto_id}");
                    }

                    if ($inventario->cantidad < $detalle->cantidad) {
                        throw new \Exception("Inventario insuficiente para cancelar.");
                    }

                    $inventario->cantidad -= $detalle->cantidad;
                    $inventario->save();
                }

                $movimiento = Movimiento::where('factura_id', $factura->id)->first();
                if ($movimiento) {
                    $movimiento->update(['cancelado' => 1]);
                }

                $factura->update([
                    'cancelado' => 1
                ]);
            });

            return back()->with('success', 'Factura cancelada correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cancelar la factura: ' . $e->getMessage());
        }
    }

    public function cargarXml(Request $request)
    {
        $request->validate([
            'xml' => 'required|file|mimes:xml'
        ]);

        try {

            DB::transaction(function () use ($request) {

                $xmlContent = file_get_contents($request->file('xml')->getRealPath());
                $xml = simplexml_load_string($xmlContent);

                $xml->registerXPathNamespace('cfdi', 'http://www.sat.gob.mx/cfd/4');

                $xml->registerXPathNamespace('tfd', 'http://www.sat.gob.mx/TimbreFiscalDigital');
                $timbre = $xml->xpath('//tfd:TimbreFiscalDigital')[0];
                $uuid = (string) $timbre['UUID'];

                if (Factura::where('uuid', $uuid)->where('cancelado', 0)->exists()) {
                    throw new \Exception("Este XML ya fue registrado.");
                }

                // Datos principales
                $comprobante = $xml;

                $folio = (string) $comprobante['Serie'] . $comprobante['Folio'];
                $fecha = (string) $comprobante['Fecha'];
                $subtotal = (float) $comprobante['SubTotal'];
                $total = (float) $comprobante['Total'];

                // Proveedor
                $emisor = $xml->xpath('//cfdi:Emisor')[0];
                $proveedor = (string) $emisor['Nombre'];
                $rfc = (string) $emisor['Rfc'];

                // Crear factura
                $factura = Factura::create([
                    'folio' => $folio,
                    'uuid' => $uuid,
                    'fecha' => $fecha,
                    'proveedor' => $proveedor,
                    'subtotal' => $subtotal,
                    'total' => $total,
                    'user_id' => auth()->id(),
                ]);

                // Conceptos
                $conceptos = $xml->xpath('//cfdi:Concepto');

                foreach ($conceptos as $concepto) {

                    $codigo = (string) $concepto['NoIdentificacion'];
                    $descripcion = (string) $concepto['Descripcion'];
                    $cantidad = (float) $concepto['Cantidad'];
                    $valorUnitario = (float) $concepto['ValorUnitario'];
                    $importe = (float) $concepto['Importe'];
                    $unidad = (string) $concepto['Unidad'];
                    $cprodserv = (string) $concepto['ClaveProdServ'];

                    // Crear producto si no existe
                    $producto = Producto::firstOrCreate(
                        ['codigo' => $codigo],
                        [
                            'descripcion' => $descripcion,
                            'unidad' => $unidad,
                            'cprodserv' => $cprodserv
                        ]
                    );

                    // Insertar detalle
                    $factura->detalles()->create([
                        'producto_id' => $producto->id,
                        'cantidad' => $cantidad,
                        'precio_unitario' => $valorUnitario,
                        'importe' => $importe,
                    ]);

                    // Sumar inventario
                    $inventario = Inventario::firstOrCreate(
                        [
                            'producto_id' => $producto->id,
                            'bodega_id' => 1 // puedes hacerlo dinámico después
                        ],
                        ['cantidad' => 0]
                    );

                    $inventario->increment('cantidad', $cantidad);
                }

            });

            return redirect()->route('facturas.index')
                ->with('success', 'Factura XML cargada correctamente.');

        } catch (\Exception $e) {

            return back()->with('error', $e->getMessage());
        }
    }
}
