@extends('layouts.stisla')

@section('title', 'Bodegas')

@section('content')
<div class="section">
    <div class="section-header">
        <h2>Bodegas</h2>
        <div class="section-header-button ml-auto">
        <a href="{{ route('bodegas.create') }}" class="btn btn-primary">Nueva Bodega</a>
        </div>
    </div>

    <div class="section-body">
    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Ubicación</th>
                            <th>Telefono</th>
                            <th>Encargado</th>
                            <th>Estatus</th>
                            <th width="150">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bodegas as $bodega)
                            <tr>
                                <td>{{ $bodega->nombre }}</td>
                                <td>{{ $bodega->ubicacion }}</td>
                                <td>{{ $bodega->telefono }}</td>
                                <td>{{ $bodega->encargado }}</td>
                                <td>
                                    @if(!$bodega->inhabilitado)
                                        <span class="badge badge-success">Activa</span>
                                    @else
                                        <span class="badge badge-danger">Inhabilitada</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('bodegas.edit', $bodega) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                    
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                {{ $bodegas->links() }}
            </div>
        </div>

    </div>

</div>
@endsection