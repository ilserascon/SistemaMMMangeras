@extends('layouts.stisla')

@section('title', 'Inicio')

@section('content')
<div class="section">
    <div class="section-header">
        <h1>Bienvenido, {{ Auth::user()->name }}</h1>
    </div>

    <div class="section-body">
        
    </div>
</div>
@endsection
