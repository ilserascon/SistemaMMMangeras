@extends('layouts.stisla')

@section('title', 'Facturas')

@section('content')
<div class="section">
    <div class="section-header">
        <h2>Facturas</h2>
        <div class="section-header-button ml-auto">
            <a href="{{ route('facturas.createManual') }}" class="btn btn-primary">Registrar Factura Manual</a>
            <button class="btn btn-success" data-toggle="modal" data-target="#modalXml">
                <i class="fas fa-file-upload"></i> Cargar XML
            </button>
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

<div class="modal fade" id="modalXml" tabindex="-1" style="z-index: 1050;">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Cargar Factura XML</h5>
                <button type="button" class="close" data-dismiss="modal">
                    &times;
                </button>
            </div>

            <form id="form-xml" 
                action="{{ route('facturas.cargarXml') }}" 
                method="POST" 
                enctype="multipart/form-data">

                @csrf

                <div class="modal-body">

                    <div class="form-group">
                        <label>Seleccionar archivo XML</label>
                        <input type="file" 
                            name="xml" 
                            class="form-control" 
                            accept=".xml" 
                            required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" 
                            class="btn btn-secondary" 
                            data-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="submit" 
                            class="btn btn-info">
                        Cargar
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Correcto',
    text: "{{ session('success') }}"
});
</script>
@endif

@if(session('error'))
<script>
Swal.fire({
    icon: 'error',
    title: 'Error',
    text: "{{ session('error') }}"
});
</script>
@endif
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