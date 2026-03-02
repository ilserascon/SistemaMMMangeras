@extends('layouts.stisla')

@section('title', 'Inventario')

@section('content')
<div class="section">
    <div class="section-header">
        <h2>Inventario</h2>
    </div>

    <div class="card">
        <div class="card-body ">
            <form method="GET" class="mb-4">
                <div class="row">

                    <div class="col-md-4">
                        <select name="bodega_id" class="form-control">
                            @foreach($bodegas as $bodega)
                                <option value="{{ $bodega->id }}"
                                    {{ request('bodega_id') == $bodega->id ? 'selected' : '' }}>
                                    {{ $bodega->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-primary">
                            Filtrar
                        </button>
                    </div>

                </div>
            </form>

            <div class="card">
                <div class="card-body table-responsive">

                    <table class="table table-bordered table-striped">

                        <thead>
                            <tr>
                                <th>Bodega</th>
                                <th>Código</th>
                                <th>Producto</th>
                                <th>Unidad</th>
                                <th>Existencia</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($inventarios as $inv)
                            <tr>
                                <td>{{ $inv->bodega->nombre }}</td>
                                <td>{{ $inv->producto->codigo }}</td>
                                <td>{{ $inv->producto->descripcion }}</td>
                                <td>{{ $inv->producto->unidad }}</td>
                                <td>
                                    @if($inv->cantidad <= 0)
                                        <span class="badge badge-danger">Sin stock</span>
                                    @elseif($inv->cantidad < 5)
                                        <span class="badge badge-warning">{{ $inv->cantidad }}</span>
                                    @else
                                        <span class="badge badge-success">{{ $inv->cantidad }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    No hay registros
                                </td>
                            </tr>
                            @endforelse
                        </tbody>

                    </table>

                    {{ $inventarios->withQueryString()->links() }}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

