@extends('layouts.stisla')

@section('title', 'Detalle Factura')

@section('content')
<div class="section">
    <div class="section-header d-flex justify-content-between align-items-center">
        <h3>Detalle de Factura</h3>
        <div>
            <a href="{{ route('facturas.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0" style="color: white;">Factura Folio: {{ $factura->folio }}</h4>
        </div>

        <div class="card-body">

            {{-- Información General --}}
            <div class="row mb-4">
                <div class="col-md-6">
                    <p><strong>Proveedor:</strong><br> {{ $factura->proveedor }}</p>
                    <p><strong>Fecha Factura:</strong><br> {{ \Carbon\Carbon::parse($factura->fecha)->format('d/m/Y') }}</p>
                </div>

                <div class="col-md-6 text-md-right">
                    <p><strong>Usuario creación:</strong><br> {{ $factura->user->name ?? 'N/A' }}</p>
                    <p><strong>Fecha creación:</strong><br> {{ $factura->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            <hr>

            {{-- Tabla de Productos --}}
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-center">Cant.</th>
                            <th>Código</th>
                            <th>Descripción</th>
                            <th>Unidad</th>
                            <th>CProdServ</th>
                            <th class="text-right">Precio Unit.</th>
                            <th class="text-right">Importe</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($factura->detalles as $detalle)
                        <tr>
                            <td class="text-center">{{ $detalle->cantidad }}</td>
                            <td>{{ $detalle->producto->codigo ?? '-' }}</td>
                            <td>{{ $detalle->producto->descripcion ?? '-' }}</td>
                            <td>{{ $detalle->producto->unidad ?? '-' }}</td>
                            <td>{{ $detalle->producto->cprodserv ?? '-' }}</td>
                            <td class="text-right">
                                ${{ number_format($detalle->precio_unitario,2) }}
                            </td>
                            <td class="text-right">
                                ${{ number_format($detalle->importe,2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <hr>

            {{-- Totales --}}
            <div class="row justify-content-end">
                <div class="col-md-4">
                    <table class="table table-borderless">
                        <tr>
                            <th>Subtotal:</th>
                            <td class="text-right">
                                ${{ number_format($factura->subtotal,2) }}
                            </td>
                        </tr>
                        <tr>
                            <th>IVA:</th>
                            <td class="text-right">
                                ${{ number_format($factura->iva,2) }}
                            </td>
                        </tr>
                        <tr class="border-top">
                            <th class="h5">Total:</th>
                            <td class="text-right h5 font-weight-bold text-primary">
                                ${{ number_format($factura->total,2) }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection