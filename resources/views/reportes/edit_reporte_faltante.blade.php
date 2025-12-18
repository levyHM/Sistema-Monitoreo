@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Editar Reporte de Faltante'])

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-body">

                    <h1 class="text-center mb-4">🧾 Editar Reporte de Error Checado</h1>

                    {{-- Errores de validación --}}
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('reporte.faltante.update', $reporte->idreporte_faltante) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Hidden para idcatalogo_faltante --}}
                        <input type="hidden" name="catalogo_faltante_idcatalogo_faltante"
                            id="catalogo_faltante_idcatalogo_faltante"
                            value="{{ $reporte->catalogo_faltante_idcatalogo_faltante }}">

                        {{-- Cliente y Fecha --}}
                        <div class="row mb-3 align-items-end">
                            <div class="col-md-4 position-relative">
                                <label class="form-label">Cliente</label>
                                <input type="text" name="cliente" id="cliente" class="form-control" autocomplete="off"
                                    value="{{ $reporte->catalogoFaltante->codigo ?? old('cliente') }}">
                                <div id="suggestions_cliente" class="list-group position-absolute w-100"
                                    style="z-index: 1000;"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Razón Social</label>
                                <input type="text" name="razon_social" id="razon_social" class="form-control" readonly
                                    value="{{ $reporte->catalogoFaltante->codigo_nombre ?? '' }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Zona</label>
                                <input type="text" name="zona" id="zona" class="form-control" readonly
                                    value="{{ $reporte->catalogoFaltante->zona ?? '' }}">
                            </div>
                            <div class="col-md-1 text-end">
                                <label class="form-label">Fecha</label>
                                <input type="text" name="fecha" class="form-control"
                                    value="{{ \Carbon\Carbon::parse($reporte->fecha)->format('Y-m-d') }}" readonly>
                            </div>
                        </div>

                        {{-- Recibe Reporte --}}
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Recibe Reporte</label>
                                <input type="text" name="recibe_reporte" class="form-control"
                                    value="{{ $reporte->recibe_reporte }}">
                            </div>
                        </div>

                        {{-- Facturas --}}
                        <h5 class="mt-4">📄 Facturas</h5>
                        <div id="factura-wrapper">
                            @foreach($reporte->reportesFalta as $index => $factura)
                            <div
                                class="factura-item card shadow-sm mb-3 border-start border-3 border-secondary position-relative bg-light p-3">
                                <div class="row g-3 align-items-end">

                                    <div class="col-md-1">
                                        <label class="form-label">Cantidad</label>
                                        <input type="number" name="facturas[{{ $index }}][cantidad]"
                                            class="form-control" value="{{ $factura->cantidad }}" required min="1"
                                            step="1">
                                    </div>

                                    <div class="col-md-2 position-relative">
                                        <label class="form-label">No. Factura</label>
                                        <input type="text" name="facturas[{{ $index }}][factura]"
                                            class="form-control factura-input" value="{{ $factura->numero_factura }}"
                                            required>
                                        <div class="list-group position-absolute w-100 factura-suggestions"
                                            style="z-index:1000;"></div>
                                    </div>

                                    <div class="col-md-1">
                                        <label class="form-label">No Parte</label>
                                        <input type="text" name="facturas[{{ $index }}][icod]" class="form-control"
                                            value="{{ $factura->catalogoProducto->icod ?? '' }}" readonly>
                                    </div>

                                    <div class="col-md-5">
                                        <label class="form-label">Descripción</label>
                                        <input type="text" name="facturas[{{ $index }}][descripcion]"
                                            class="form-control" value="{{ $factura->catalogoProducto->idescr ?? '' }}"
                                            disabled required>
                                    </div>

                                    <div class="col-md-1">
                                        <label class="form-label">P. Unitario</label>
                                        <input type="number" step="0.00001" name="facturas[{{ $index }}][p_unitario]"
                                            class="form-control"
                                            value="{{ $factura->catalogoProducto->aiprecio ?? '' }}" disabled required>
                                    </div>

                                    <div class="col-md-1">
                                        <label class="form-label">Checo</label>
                                        <input type="text" name="facturas[{{ $index }}][checo]" class="form-control"
                                            value="{{ $factura->checo }}">
                                    </div>

                                    <div class="col-md-1">
                                        <label class="form-label">Empaco</label>
                                        <input type="text" name="facturas[{{ $index }}][empaco]" class="form-control"
                                            value="{{ $factura->empaco }}">
                                    </div>

                                    <input type="hidden" name="facturas[{{ $index }}][catalogo_idcatalogo]"
                                        value="{{ $factura->catalogo_idcatalogo }}">
                                </div>

                                <div class="remove-factura text-danger position-absolute top-0 end-0 p-2" role="button"
                                    style="cursor: pointer; {{ $index == 0 ? 'display:none;' : '' }}">
                                    🗑️
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <button type="button" id="add-factura" class="btn btn-primary mb-3">Agregar Factura</button>

                        {{-- Motivo y Solución --}}
                        <div class="mb-3">
                            <label class="form-label">Motivo faltante</label>
                            <select name="motivo_faltante_id" class="form-control">
                                <option value="">-- Seleccione un motivo --</option>
                                @foreach($motivos as $motivo)
                                <option value="{{ $motivo->id }}" {{ old('motivo_faltante_id', $reporte->
                                    motivo_faltante_id) == $motivo->id ? 'selected' : '' }}>
                                    {{ $motivo->descripcion }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Solución</label>
                            <input type="text" name="solucion" class="form-control" value="{{ $reporte->solucion }}">
                        </div>

                        {{-- Tipo de solución --}}
                        <div class="row mb-3">
                            <div class="col-md-3 form-check">
                                <input type="radio" name="catalogo_reporte_faltante_tipo_id" value="1"
                                    class="form-check-input" id="tipo_no_procede" {{
                                    old('catalogo_reporte_faltante_tipo_id',
                                    $reporte->catalogo_reporte_faltante_tipo_id) == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="tipo_no_procede">No Procede</label>
                            </div>

                            <div class="col-md-3 form-check">
                                <input type="radio" name="catalogo_reporte_faltante_tipo_id" value="2"
                                    class="form-check-input" id="tipo_procede" {{
                                    old('catalogo_reporte_faltante_tipo_id',
                                    $reporte->catalogo_reporte_faltante_tipo_id) == 2 ? 'checked' : '' }}>
                                <label class="form-check-label" for="tipo_procede">Procede</label>
                            </div>

                            <div class="col-md-3 form-check">
                                <input type="radio" name="catalogo_reporte_faltante_tipo_id" value="3"
                                    class="form-check-input" id="tipo_cambio_fisico" {{
                                    old('catalogo_reporte_faltante_tipo_id',
                                    $reporte->catalogo_reporte_faltante_tipo_id) == 3 ? 'checked' : '' }}>
                                <label class="form-check-label" for="tipo_cambio_fisico">Cambio Físico</label>
                            </div>

                            <div class="col-md-3 form-check">
                                <input type="radio" name="catalogo_reporte_faltante_tipo_id" value="4"
                                    class="form-check-input" id="tipo_nc_servicio" {{
                                    old('catalogo_reporte_faltante_tipo_id',
                                    $reporte->catalogo_reporte_faltante_tipo_id) == 4 ? 'checked' : '' }}>
                                <label class="form-check-label" for="tipo_nc_servicio">NC Servicio</label>
                            </div>
                        </div>


                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-success btn-lg">Guardar Cambios</button>
                            <a href="{{ route('reporte.faltante.show', $reporte->idreporte_faltante) }}"
                                class="btn btn-secondary btn-lg ms-2">Cancelar y Volver</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){

    // === AUTOCOMPLETADO CLIENTE ===
    $('#cliente').on('input', function(){
        let query = $(this).val().trim();
        if(!query || query.length < 2){
            $('#suggestions_cliente').empty().hide();
            $('#razon_social,#zona').val('');
            $('#catalogo_faltante_idcatalogo_faltante').val('');
            return;
        }

        $.get('{{ route("clientes.buscar") }}', { query }, function(data){
            let html = data.length
                ? data.map(item => `
                    <div class="list-group-item list-group-item-action" role="button"
                        onclick="seleccionarCliente('${item.codigo}','${item.codigo_nombre}','${item.zona}','${item.idcatalogo_faltante}')">
                        ${item.codigo} - ${item.codigo_nombre}
                    </div>`).join('')
                : '<div class="list-group-item">Sin coincidencias</div>';
            $('#suggestions_cliente').html(html).show();
        });
    });

    window.seleccionarCliente = function(codigo, nombre, zona, id){
        $('#cliente').val(codigo);
        $('#razon_social').val(nombre);
        $('#zona').val(zona);
        $('#catalogo_faltante_idcatalogo_faltante').val(id);
        $('#suggestions_cliente').empty().hide();
    };

    // === AGREGAR / CLONAR FACTURA ===
    let facturaIndex = {{ count($reporte->reportesFalta) }};
    $('#add-factura').click(function(){
        let clone = $('.factura-item').first().clone();

        clone.find('input').each(function(){
            let name = $(this).attr('name');
            if(name) $(this).attr('name', name.replace(/\d+/, facturaIndex));
            if($(this).attr('id')) $(this).attr('id', $(this).attr('id').replace(/\d+/, facturaIndex));
            $(this).val('');
        });

        clone.find('input[name$="[catalogo_idcatalogo]"]').val('');
        clone.find('.remove-factura').show();
        $('#factura-wrapper').append(clone);
        facturaIndex++;
    });

    $(document).on('click', '.remove-factura', function(){
        $(this).closest('.factura-item').remove();
    });

    // === AUTOCOMPLETADO FACTURA (ahora con cliente obligatorio) ===
    $(document).on('input', 'input[name$="[factura]"]', function() {
        let input = $(this);
        let query = input.val().trim();
        let suggestions = input.siblings('.factura-suggestions');

        if(query.length < 2){
            suggestions.empty().hide();
            return;
        }

        let clicod = $('#cliente').val().trim();
        if(!clicod){
            suggestions.html('<div class="list-group-item text-danger">Seleccione un cliente primero</div>').show();
            return;
        }

        $.get('{{ route("facturas.buscar") }}', { query, clicod }, function(data){
            let html = data.length
                ? data.map(item => `
                    <div class="list-group-item list-group-item-action" role="button"
                        onclick='seleccionarFactura(${JSON.stringify(item)}, this)'>
                        ${item.dnum} → <b>${item.icod}</b>
                    </div>`).join('')
                : '<div class="list-group-item">Sin coincidencias</div>';
            suggestions.html(html).show();
        });
    });

    // === SELECCIONAR FACTURA ===
    window.seleccionarFactura = function(producto, inputEl) {
        let row = $(inputEl).closest('.factura-item');

        row.find('input[name$="[factura]"]').val(producto.dnum);
        row.find('input[name$="[descripcion]"]').val(producto.idescr);
        row.find('input[name$="[icod]"]').val(producto.icod);
        row.find('input[name$="[p_unitario]"]').val(producto.aiprecio);

        // Asignar cantidad si está vacía
        let cantidadInput = row.find('input[name$="[cantidad]"]');
        if(!cantidadInput.val()) {
            cantidadInput.val(producto.aicant);
        }

        // Asignar ID de producto oculto
        row.find('input[name$="[catalogo_idcatalogo]"]').val(producto.idcatalogoproducto);

        // Ocultar sugerencias
        row.find('.factura-suggestions').empty().hide();
    };

});
</script>
