@extends('layouts.stisla')

@section('title', 'Traslados')

@section('content')
<div class="section">
    <div class="section-header">
        <h2>Traslados</h2>
        <div class="section-header-button ml-auto">
            <a href="{{ route('traslados.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Nuevo Traslado
            </a>
        </div>
    </div>

    <div class="section-body">
        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card">
            <div class="card-body table-responsive">
                <form method="GET" class="mb-3">
                    <div class="row">

                        <div class="col-md-3">
                            <label>Bodega Origen</label>
                            <select name="bodega_origen_id" class="form-control">
                                <option value="">Todas</option>
                                @foreach($bodegas as $b)
                                    <option value="{{ $b->id }}"
                                        {{ request('bodega_origen_id') == $b->id ? 'selected' : '' }}>
                                        {{ $b->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Bodega Destino</label>
                            <select name="bodega_destino_id" class="form-control">
                                <option value="">Todas</option>
                                @foreach($bodegas as $b)
                                    <option value="{{ $b->id }}"
                                        {{ request('bodega_destino_id') == $b->id ? 'selected' : '' }}>
                                        {{ $b->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label>Fecha Inicio</label>
                            <input type="date" name="fecha_inicio"
                                value="{{ request('fecha_inicio') }}"
                                class="form-control">
                        </div>

                        <div class="col-md-2">
                            <label>Fecha Fin</label>
                            <input type="date" name="fecha_fin"
                                value="{{ request('fecha_fin') }}"
                                class="form-control">
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-primary w-100">Filtrar</button>
                        </div>
                    </div>
                </form>

                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Origen</th>
                        <th>Destino</th>
                        <th>Usuario</th>
                        <th>Fecha</th>
                        <th>&nbsp;</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>

                    <tbody>
                        @forelse($traslados as $t)
                        <tr>
                            <td>{{ $t->id }}</td>
                            <td>{{ $t->bodegaOrigen->nombre }}</td>
                            <td>{{ $t->bodegaDestino->nombre }}</td>
                            <td>{{ $t->user->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($t->fecha)->format('d/m/Y') }}</td>
                            <td>
                                @if($t->cancelado)
                                    <span class="badge badge-danger">Cancelado</span>
                                @endif

                            </td>
                            <td>
                                <a href="{{ route('traslados.show', $t) }}" 
                                class="btn btn-info btn-sm"
                                title="Ver detalle">
                                    <i class="fas fa-list"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center">Sin registros</td></tr>
                        @endforelse
                    </tbody>
                </table>

                {{ $traslados->withQueryString()->links() }}

            </div>
        </div>
    </div>

</div>

@endsection
