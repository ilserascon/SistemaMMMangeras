@extends('layouts.stisla')

@section('title', 'Facturas')

@section('content')
<div class="section">
    <div class="section-header">
        <h2>Facturas</h2>
        <div class="section-header-button ml-auto">
            <a href="{{ route('facturas.createManual') }}" class="btn btn-primary">Cargar Factura Manual</a>
            <a href="{{ route('facturas.createManual') }}" class="btn btn-info"><i class="fas fa-file-upload"></i> Cargar Factura XML</a>
        </div>
    </div>

    <div class="section-body">
    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

        <div class="card">
            <div class="card-body table-responsive">

            <form method="GET" action="{{ route('facturas.index') }}" class="row mb-3">

                <div class="col-md-3">
                    <label>Folio</label>
                    <input type="text" name="folio" class="form-control"
                        value="{{ request('folio') }}">
                </div>

                <div class="col-md-3">
                    <label>Fecha inicio</label>
                    <input type="date" name="fecha_inicio" class="form-control"
                        value="{{ request('fecha_inicio') }}">
                </div>

                <div class="col-md-3">
                    <label>Fecha fin</label>
                    <input type="date" name="fecha_fin" class="form-control"
                        value="{{ request('fecha_fin') }}">
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary me-2">Filtrar</button>&nbsp; 
                    <a href="{{ route('facturas.index') }}" class="btn btn-secondary">Limpiar</a>
                </div>

            </form>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Folio</th>
                            <th>Proveedor</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Usuario</th>
                            <th>&nbsp;</th>
                            <th width="150">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($facturas as $factura)
                            <tr>
                                <td>{{ $factura->folio }}</td>
                                <td>{{ $factura->proveedor }}</td>
                                <td>{{ $factura->fecha }}</td>
                                <td>{{ number_format($factura->total, 2) }}</td>
                                <td>{{ $factura->user->name }}</td>
                                <td>
                                    @if($factura->cancelado)
                                        <span class="badge badge-danger">Cancelada</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('facturas.show', $factura) }}" class="btn btn-info btn-sm" title="Ver Factura"><i class="fas fa-file"></i> </a>
                                    <a href="{{ route('facturas.edit', $factura) }}" class="btn btn-danger btn-sm" title="Cancelar Factura"><i class="fas fa-times"></i> </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                {{ $facturas->appends(request()->query())->links() }}
            </div>
        </div>

    </div>

</div>
@endsection