@extends('layouts.stisla')

@section('title', 'Facturas')

@section('content')
<div class="section">
    <div class="section-header">
        <h2>Facturas</h2>
        <div class="section-header-button ml-auto">
            <a href="{{ route('facturas.createManual') }}" class="btn btn-primary">Cargar Factura Manual</a>
            <a href="{{ route('facturas.createManual') }}" class="btn btn-info"><i class="fas fa-file-upload"></i> Cargar Factura XML</a>
        </div>
    </div>

    <div class="section-body">
    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

        <div class="card">
            <div class="card-body table-responsive">

            <form method="GET" action="{{ route('facturas.index') }}" class="row mb-3">

                <div class="col-md-3">
                    <label>Folio</label>
                    <input type="text" name="folio" class="form-control"
                        value="{{ request('folio') }}">
                </div>

                <div class="col-md-3">
                    <label>Fecha inicio</label>
                    <input type="date" name="fecha_inicio" class="form-control"
                        value="{{ request('fecha_inicio') }}">
                </div>

                <div class="col-md-3">
                    <label>Fecha fin</label>
                    <input type="date" name="fecha_fin" class="form-control"
                        value="{{ request('fecha_fin') }}">
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary me-2">Filtrar</button>&nbsp; 
                    <a href="{{ route('facturas.index') }}" class="btn btn-secondary">Limpiar</a>
                </div>

            </form>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Folio</th>
                            <th>Proveedor</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Usuario</th>
                            <th>&nbsp;</th>
                            <th width="150">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($facturas as $factura)
                            <tr>
                                <td>{{ $factura->folio }}</td>
                                <td>{{ $factura->proveedor }}</td>
                                <td>{{ $factura->fecha }}</td>
                                <td>{{ number_format($factura->total, 2) }}</td>
                                <td>{{ $factura->user->name }}</td>
                                <td>
                                    @if($factura->cancelado)
                                        <span class="badge badge-danger">Cancelada</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('facturas.show', $factura) }}" class="btn btn-info btn-sm" title="Ver Factura"><i class="fas fa-file"></i> </a>
                                    @if($factura->cancelado == 0)
                                        <button class="btn btn-danger btn-sm btn-cancelar" data-id="{{ $factura->id }}" data-folio="{{ $factura->folio }}" title="Cancelar Factura">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                {{ $facturas->appends(request()->query())->links() }}
            </div>
        </div>

    </div>

    <form id="form-cancelar" method="POST" style="display:none;">
        @csrf
    </form>

</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.btn-cancelar').forEach(button => {

        button.addEventListener('click', function () {

            let facturaId = this.dataset.id;
            let folio = this.dataset.folio;

            Swal.fire({
                title: '¿Cancelar factura?',
                html: `
                    <strong>Folio:</strong> ${folio} <br><br>
                    Esta acción descontará los productos del inventario.
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, cancelar',
                cancelButtonText: 'No',
                reverseButtons: true
            }).then((result) => {

                if (result.isConfirmed) {

                    let form = document.getElementById('form-cancelar');
                    form.action = `/facturas/${facturaId}/cancelar`;
                    form.submit();
                }
            });

        });

    });

});
</script>
@endsection