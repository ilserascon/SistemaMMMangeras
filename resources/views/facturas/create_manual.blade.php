@extends('layouts.stisla')

@section('title', 'Captura Manual de Factura')

@section('content')
<div class="container">
    <h2>Captura Manual de Factura</h2>

    <div class="section-body">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @error('productos')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="card">
        <div class="card-body">
            <form action="{{ route('facturas.storeManual') }}" method="POST" id="facturaForm">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <label>Folio</label>
                        <input type="text" name="folio" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>Fecha</label>
                        <input type="date" name="fecha" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Proveedor</label>
                        <input type="text" name="proveedor" class="form-control" required>
                    </div>
                </div>

                <hr>

                <h4>
                    Productos 
                    <button type="button" class="btn btn-warning btn-sm" id="addProducto"><i class="fas fa-plus"></i> Agregar</button>
                </h4>

                <table class="table table-bordered" id="productosTable">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Descripción</th>
                            <th>Unidad</th>
                            <th>C. ProdServ</th>
                            <th>Cantidad</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

                <div class="mt-3">
                    <button class="btn btn-success">Guardar Factura</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>

$(document).ready(function() {
    $('#addProducto').click(function() {
        let index = $('#productosTable tbody tr').length;

        let row = `<tr>
            <td><input type="text" name="productos[${index}][codigo]" class="form-control codigoInput" required></td>
            <td><input type="text" name="productos[${index}][descripcion]" class="form-control descripcionInput" required></td>
            <td><input type="text" name="productos[${index}][unidad]" class="form-control unidadInput" required></td>
            <td><input type="text" name="productos[${index}][cprodserv]" class="form-control cProdServInput" required></td>
            <td><input type="number" name="productos[${index}][cantidad]" class="form-control cantidadInput" value="1" step="0.01" min="0.01" required></td>
            <td><button type="button" class="btn btn-danger removeRow">Eliminar</button></td>
        </tr>`;
        $('#productosTable tbody').append(row);
    });

    $('#productosTable').on('click', '.removeRow', function() {
        $(this).closest('tr').remove();
    });
});
</script>
@endsection