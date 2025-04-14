@extends('layouts.stisla')

@section('title', 'Inicio')

@section('content')
<div class="section">
  <div class="section-header">
    <h1>Dashboard</h1>
  </div>

  <div class="section-body">
    <div class="row">
      <div class="col-lg-4 col-md-6 col-sm-6">
        <div class="card card-statistic-1">
          <div class="card-icon bg-primary">
            <i class="fas fa-users"></i>
          </div>
          <div class="card-wrap">
            <div class="card-header">
              <h4>Usuarios</h4>
            </div>
            <div class="card-body">
              {{ \App\Models\User::count() }}
            </div>
          </div>
        </div>
      </div>
      <!-- Puedes agregar más tarjetas aquí -->
    </div>
  </div>
</div>
@endsection
