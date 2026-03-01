@extends('layouts.stisla')

@section('title', 'Usuarios')

@section('content')
<div class="section">
  <div class="section-header">
    <h2>Usuarios</h2>
    <div class="section-header-button ml-auto">
      <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Nuevo Usuario</a>
    </div>
  </div>

  <div class="section-body">
    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
      <div class="card-body table-responsive">
        <table class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Email</th>
              <th>Creado</th>
              <th>Rol</th>
              <th>Estatus</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($users as $user)
              <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                <td>{{ $user->role->nombre ?? '-' }}</td>
                <td>
                    @if(!$user->inhabilitado)
                        <span class="badge badge-success">Activo</span>
                    @else
                        <span class="badge badge-danger">Inhabilitado</span>
                    @endif
                </td>
                <td>
                  <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>

                </td>
              </tr>
            @empty
              <tr><td colspan="6" class="text-center">No hay usuarios registrados.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
