@extends('layouts.stisla')

@section('title', 'Registrar Traslado')
@section('content')
<div class="section">
    <div class="section-header">
        <h2>Registrar Traslado</h2>
    </div>
    <div class="section-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('traslados.store') }}">
                    @csrf

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Bodega Origen</label>
                                <select name="bodega_origen_id" class="form-control" required>
                                    @foreach($bodegas as $bodega)
                                        <option value="{{ $bodega->id }}"
                                            {{ old('bodega_origen_id') == $bodega->id ? 'selected' : '' }}>
                                            {{ $bodega->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Bodega Destino</label>
                                <select name="bodega_destino_id" class="form-control" required>
                                    <option value="">Seleccione...</option>
                                    @foreach($bodegas as $bodega)
                                        <option value="{{ $bodega->id }}"
                                            {{ old('bodega_destino_id') == $bodega->id ? 'selected' : '' }}>
                                            {{ $bodega->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>
                    <hr>
                    <div class="row col-md-12">

                            <div class="d-flex justify-content-between mb-2">
                                <h5>Productos</h5> &nbsp;
                                <button type="button" id="btnAgregarProducto" class="btn btn-warning btn-sm">
                                    + Agregar
                                </button>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered" id="tablaProductos">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="15%">Código</th>
                                            <th width="35%">Descripción</th>
                                            <th width="10%">Stock</th>
                                            <th width="15%">Cantidad</th>
                                            <th width="5%"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                        </div>

                    </div>

                    <div class="text-right mt-3">
                        <a href="{{ route('traslados.index') }}" class="btn btn-secondary">Cancelar</a>

                        <button type="submit" class="btn btn-success">
                            Guardar Traslado
                        </button>
                    </div>
                </form>
            </div>
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
let index = 0;

$('#btnAgregarProducto').click(function(){

    $('#tablaProductos tbody').append(`
        <tr>
            <td>
                <input type="text" 
                    name="productos[${index}][codigo]" 
                    class="form-control codigoProducto" required>

                <input type="hidden" 
                    name="productos[${index}][producto_id]" 
                    class="productoId">
            </td>

            <td class="descripcionProducto text-left"></td>

            <td>
                <span class="badge badge-info stockProducto" 
                    data-stock="0">0</span>
            </td>

            <td>
                <input type="number" step="0.01"
                    name="productos[${index}][cantidad]" 
                    class="form-control cantidadProducto" required>
            </td>

            <td>
                <button type="button" 
                        class="btn btn-danger btn-sm btnEliminar">
                    ✕
                </button>
            </td>
        </tr>
    `);

    index++;
});

// Eliminar fila
$(document).on('click', '.btnEliminar', function(){
    $(this).closest('tr').remove();
});


// Buscar producto al salir del input
$(document).on('blur', '.codigoProducto', function(){

    let row = $(this).closest('tr');
    let codigo = $(this).val();
    let bodegaOrigen = $('select[name="bodega_origen_id"]').val();

    if(!codigo){
        Swal.fire('Error', 'Ingrese el código del producto', 'error');
        return;
    }
    if(!bodegaOrigen){
        Swal.fire('Error', 'Seleccione bodega origen primero', 'error');
        return;
    }

    $.ajax({
        url: "{{ route('productos.buscar') }}",
        data: {
            codigo: codigo,
            bodega_id: bodegaOrigen
        },
        success: function(response){ 
            row.find('.productoId').val(response.id);
            row.find('.descripcionProducto').text(response.descripcion);
            row.find('.stockProducto')
                .text(response.stock)
                .attr('data-stock', response.stock);

        },
        error: function(){
            Swal.fire('Error', 'Producto no encontrado', 'error');
        }
    });

});


// Validar cantidad vs stock
$(document).on('blur', '.cantidadProducto', function(){

    let row = $(this).closest('tr');
    let cantidad = parseFloat($(this).val());
    let stock = parseFloat(row.find('.stockProducto').attr('data-stock'));

    if(cantidad > stock){
        Swal.fire('Error', 'Cantidad supera el stock disponible', 'error');
        $(this).val('');
    }

});


// Actualizar stock si cambia bodega origen
$('select[name="bodega_origen_id"]').change(function(){

    $('#tablaProductos tbody tr').each(function(){

        let row = $(this);
        let codigo = row.find('.codigoProducto').val();

        if(!codigo) return;

        $.ajax({
            url: "{{ route('productos.buscar') }}",
            data: {
                codigo: codigo,
                bodega_id: $('select[name="bodega_origen_id"]').val()
            },
            success: function(response){
                row.find('.stockProducto')
                    .text(response.stock)
                    .attr('data-stock', response.stock);
            }
        });

    });

});
</script>
@endsection