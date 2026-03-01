@extends('layouts.stisla')

@section('title', 'Editar Producto')


@section('content')
<div class="container">
    <h2>Editar Producto</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('productos.update', $producto) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Código</label>
            <input            type="text" 
                name="codigo" 
                class="form-control @error('codigo') is-invalid @enderror"
                value="{{ old('codigo', $producto->codigo) }}"      
            >
            @error('codigo')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea 
                name="descripcion" 
                class="form-control @error('descripcion') is-invalid @enderror"
                rows="3"
            >{{ old('descripcion', $producto->descripcion) }}</textarea>
            @error('descripcion')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Unidad</label>
            <input 
                type="text" 
                name="unidad" 
                class="form-control @error('unidad') is-invalid @enderror"
                value="{{ old('unidad', $producto->unidad) }}"
            >
            @error('unidad')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">C. ProdServ</label>
            <input 
                type="text" 
                name="cprodserv" 
                class="form-control @error('cprodserv') is-invalid @enderror"
                value="{{ old('cprodserv', $producto->cprodserv) }}"
            >
            @error('cprodserv')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <button class="btn btn-success">Actualizar</button>
        <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection