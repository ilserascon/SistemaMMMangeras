@extends('layouts.stisla')

@section('title', 'Editar Bodega')

@section('content')
<div class="container">
    <h2>Editar Bodega</h2>

    <form action="{{ route('bodegas.update', $bodega) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" 
                   name="nombre" 
                   class="form-control"
                   value="{{ old('nombre', $bodega->nombre) }}">
        </div>

        <div class="mb-3">
            <label>Ubicación</label>
            <input type="text" 
                   name="ubicacion" 
                   class="form-control"
                   value="{{ old('ubicacion', $bodega->ubicacion) }}">
        </div>
        
        <div class="mb-3">
            <label>Teléfono</label>
            <input type="text" 
                name="telefono" 
                class="form-control"
                value="{{ old('telefono', $bodega->telefono) }}">
        </div>

        <div class="mb-3">
            <label>Encargado</label>
            <input type="text" 
                   name="encargado" 
                   class="form-control"
                   value="{{ old('encargado', $bodega->encargado) }}">
        </div>

        @if ($bodega->id !== 1)
        <div class="mb-3">
            <label for="inhabilitado">Estatus</label>
            <select name="inhabilitado" class="form-control @error('inhabilitado') is-invalid @enderror" required>
                <option value="0" {{ !$bodega->inhabilitado ? 'selected' : '' }}>Activa</option>
                <option value="1" {{ $bodega->inhabilitado ? 'selected' : '' }}>Inhabilitada</option>
            </select>
            @error('inhabilitado') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        @endif

        <button class="btn btn-success">Actualizar</button>
        <a href="{{ route('bodegas.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection