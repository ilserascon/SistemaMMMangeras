@extends('layouts.stisla')

@section('title', 'Nuevo Producto')

@section('content')
<div class="container">
    <h2>Nuevo Producto</h2>

    <form action="{{ route('productos.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Código</label>
            <input type="text" 
                   name="codigo" 
                   class="form-control"
                   value="{{ old('codigo') }}">
        </div>

        <div class="mb-3">
            <label>Descripción</label>
            <textarea name="descripcion" 
                      class="form-control">{{ old('descripcion') }}</textarea>
        </div>
        <div class="mb-3">
            <label>Unidad</label>
            <input type="text" 
                   name="unidad" 
                   class="form-control"
                   value="{{ old('unidad') }}">
        </div>
        <div class="mb-3">
            <label>C. ProdServ</label>
            <input type="text" 
                   name="cprodserv" 
                   class="form-control"
                   value="{{ old('cprodserv') }}">
        </div>

        <button class="btn btn-success">Guardar</button>
        <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection