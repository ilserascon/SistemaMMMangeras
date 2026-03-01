@extends('layouts.stisla')

@section('title', 'Nueva Bodega')

@section('content')
<div class="container">
    <h2>Nueva Bodega</h2>

    <form action="{{ route('bodegas.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" 
                   name="nombre" 
                   class="form-control @error('nombre') is-invalid @enderror"
                   value="{{ old('nombre') }}">
            @error('nombre')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Ubicación</label>
            <input type="text" 
                   name="ubicacion" 
                   class="form-control"
                   value="{{ old('ubicacion') }}">
        </div>

        <div class="mb-3">
            <label>Teléfono</label>
            <input type="text" 
                   name="telefono" 
                   class="form-control"
                   value="{{ old('telefono') }}">
        </div>

        <div class="mb-3">
            <label>Encargado</label>
            <input type="text" 
                   name="encargado" 
                   class="form-control"
                   value="{{ old('encargado') }}">
        </div>

        <button class="btn btn-success">Guardar</button>
        <a href="{{ route('bodegas.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection