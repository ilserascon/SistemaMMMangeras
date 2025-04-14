@extends('layouts.stisla')

@section('title', 'Nuevo Usuario')

@section('content')
<div class="section">
  <div class="section-header">
    <h1>Nuevo Usuario</h1>
  </div>

  <div class="section-body">
    <div class="card">
      <div class="card-body">
        <form method="POST" action="{{ route('admin.users.store') }}">
          @csrf

          <div class="form-group">
            <label for="name">Nombre</label>
            <input name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="form-group">
            <label for="email">Correo electrónico</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

            <div class="form-group">
                <label for="role_id">Rol</label>
                <select name="role_id" class="form-control @error('role_id') is-invalid @enderror" required>
                    <option value="">Seleccione un rol</option>
                    @foreach ($roles as $role)
                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                        {{ $role->nombre }}
                    </option>
                    @endforeach
                </select>
                @error('role_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>


          <button type="submit" class="btn btn-primary">Guardar</button>
          <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
