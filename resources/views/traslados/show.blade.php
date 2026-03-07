@extends('layouts.stisla')

@section('title', 'Detalle de Traslado')

@section('content')

<div class="section">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="section-header">
        <h4>Traslado #{{ $traslado->id }}</h4>
    </div>

    <div class="card">

        <div class="card-body">

            {{-- INFORMACIÓN GENERAL --}}
            <div class="row mb-4">

                <div class="col-md-4">
                    <strong>Bodega Origen:</strong><br>
                    {{ $traslado->bodegaOrigen->nombre }}
                </div>

                <div class="col-md-4">
                    <strong>Bodega Destino:</strong><br>
                    {{ $traslado->bodegaDestino->nombre }}
                </div>

                <div class="col-md-4">
                    <strong>Usuario:</strong><br>
                    {{ $traslado->user->name }}
                </div>

            </div>

            <div class="row mb-4">

                <div class="col-md-4">
                    <strong>Fecha:</strong><br>
                    {{ $traslado->created_at->format('d/m/Y H:i') }}
                </div>

                <div class="col-md-4">
                    <strong>Estado:</strong><br>

                    @if($traslado->cancelado)
                        <span class="badge badge-danger">Cancelado</span>
                    @else
                        <span class="badge badge-success">Activo</span>
                    @endif

                </div>

            </div>

            {{-- PRODUCTOS --}}
            <h5>Productos trasladados</h5>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">

                    <thead>
                        <tr>
                            <th width="15%">Código</th>
                            <th width="45%">Descripción</th>
                            <th width="20%">Cantidad</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($traslado->movimientoDetalles as $detalle)

                        <tr>
                            <td>{{ $detalle->producto->codigo }}</td>

                            <td>{{ $detalle->producto->descripcion }}</td>

                            <td>{{ number_format($detalle->cantidad,2) }}</td>
                        </tr>

                        @endforeach

                    </tbody>

                </table>
            </div>

        </div>

    </div>

</div>

@endsection