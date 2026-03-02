@extends('layouts.stisla')

@section('title', 'Productos')

@section('content')
<div class="section">
    <div class="section-header">
        <h2>Productos</h2>
        <div class="section-header-button ml-auto">
            <a href="{{ route('productos.create') }}" class="btn btn-primary">Nuevo Producto</a>
        </div>
    </div>

    <div class="section-body">
        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-body table-responsive">
                <form method="GET" class="mb-4">
                    <div class="row">

                        <div class="col-md-4">
                            <label>Código</label>
                            <input type="text" name="codigo" class="form-control" value="{{ request('codigo') }}">
                        </div>
                        <div class="col-md-4">
                            <label>Descripción</label>
                            <input type="text" name="descripcion" class="form-control" value="{{ request('descripcion') }}">
                        </div>

                        <div class="col-md-4 d-flex align-items-end">
                            <button class="btn btn-primary me-2">Filtrar</button>&nbsp; 
                            <a href="{{ route('productos.index') }}" class="btn btn-secondary">Limpiar</a>
                        </div>

                    </div>
                </form>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Codigo</th>
                            <th>Descripción</th>
                            <th>Unidad</th>
                            <th>C.ProdServ</th>
                            <th width="150">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productos as $producto)
                            <tr>
                                <td>{{ $producto->codigo }}</td>
                                <td>{{ $producto->descripcion }}</td>
                                <td>{{ $producto->unidad }}</td>
                                <td>{{ $producto->cprodserv }}</td>
                                <td>
                                    <a href="{{ route('productos.edit', $producto) }}" 
                                    class="btn btn-sm btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                {{ $productos->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection