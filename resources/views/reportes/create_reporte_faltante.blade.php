@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Reporte de Faltante'])

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-body">

                    <h1 class="text-center mb-4">🧾 Crear Reporte de Faltante</h1>

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

                    <form action="{{ route('reporte.faltante.store') }}" method="POST">
                        @csrf

                        {{-- Hidden para idcatalogo_faltante --}}
                        <input type="hidden" name="catalogo_faltante_idcatalogo_faltante"
                            id="catalogo_faltante_idcatalogo_faltante">

                        {{-- Cliente y Fecha --}}
                        <div class="row mb-3 align-items-end">
                            <div class="col-md-4 position-relative">
                                <label class="form-label">Cliente</label>
                                <input type="text" name="cliente" id="cliente" class="form-control" autocomplete="off"
                                    value="{{ old('cliente') }}">
                                <div id="suggestions_cliente" class="list-group position-absolute w-100"
                                    style="z-index: 1000;"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Razón Social</label>
                                <input type="text" name="razon_social" id="razon_social" class="form-control" readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Zona</label>
                                <input type="text" name="zona" id="zona" class="form-control" readonly>
                            </div>
                            <div class="col-md-1 text-end">
                                <label class="form-label">Fecha</label>
                                <input type="text" name="fecha" class="form-control" value="{{ date('Y-m-d') }}"
                                    readonly>
                            </div>
                        </div>

                        {{-- Recibe Reporte --}}
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Recibe Reporte</label>
                                <input type="text" name="recibe_reporte" class="form-control"
                                    value="{{ old('recibe_reporte') }}">
                            </div>
                        </div>

                        {{-- Facturas --}}
                        <h5 class="mt-4">📄 Facturas</h5>
                        <div id="factura-wrapper">
                            <div
                                class="factura-item card shadow-sm mb-3 border-start border-3 border-secondary position-relative bg-light p-3">
                                <div class="row g-3 align-items-end">

                                    <div class="col-md-1">
                                        <label class="form-label">Cantidad</label>
                                        <input type="number" name="facturas[0][cantidad]" id="cantidad_0"
                                            class="form-control" required min="1" step="1" pattern="\d+">
                                    </div>

                                    <div class="col-md-2 position-relative">
                                        <label class="form-label">No. Factura</label>
                                        <input type="text" name="facturas[0][factura]" id="factura_0"
                                            class="form-control" required>
                                        <div id="factura_0_suggestions"
                                            class="list-group position-absolute w-100 factura-suggestions"
                                            style="z-index:1000;"></div>
                                    </div>

                                    <div class="col-md-1">
                                        <label class="form-label">No Parte</label>
                                        <input type="text" name="facturas[0][icod]" id="icod_0" class="form-control"
                                            readonly>
                                    </div>

                                    <div class="col-md-5">
                                        <label class="form-label">Descripción</label>
                                        <input type="text" name="facturas[0][descripcion]" id="descripcion_0"
                                            class="form-control" disabled required>
                                    </div>

                                    <div class="col-md-1">
                                        <label class="form-label">P. Unitario</label>
                                        <input type="number" step="0.00001" name="facturas[0][p_unitario]"
                                            id="p_unitario_0" class="form-control" disabled required>
                                    </div>

                                    <div class="col-md-1">
                                        <label class="form-label">Checo</label>
                                        <input type="text" name="facturas[0][checo]" id="checo_0" class="form-control">
                                    </div>

                                    <div class="col-md-1">
                                        <label class="form-label">Empaco</label>
                                        <input type="text" name="facturas[0][empaco]" id="empaco_0"
                                            class="form-control">
                                    </div>

                                    {{-- Hidden catalogo_idcatalogo --}}
                                    <input type="hidden" name="facturas[0][catalogo_idcatalogo]"
                                        id="catalogo_idcatalogo_0">

                                </div>

                                <div class="remove-factura text-danger position-absolute top-0 end-0 p-2" role="button"
                                    style="cursor: pointer; display: none;">
                                    🗑️
                                </div>
                            </div>
                        </div>

                        <button type="button" id="add-factura" class="btn btn-primary mb-3">Agregar Factura</button>

                        {{-- Motivo faltante --}}
                        <div class="mb-3">
                            <label class="form-label">Motivo faltante</label>
                            <select name="motivo_faltante_id" class="form-control">
                                <option value="">-- Seleccione un motivo --</option>
                                @foreach($motivos as $motivo)
                                <option value="{{ $motivo->id }}" {{ old('motivo_faltante_id')==$motivo->id ? 'selected'
                                    : '' }}>
                                    {{ $motivo->descripcion }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Solución --}}
                        <div class="mb-3">
                            <label class="form-label">Solución</label>
                            <input type="text" name="solucion" class="form-control" value="{{ old('solucion') }}">
                        </div>

                        {{-- Autorizaciones y flags --}}
                        <div class="row mb-3">
                            @php
                            $tipoSeleccionado = old('catalogo_reporte_faltante_tipo_id',
                            $reporte->catalogo_reporte_faltante_tipo_id ?? null);
                            @endphp


                            <div class="col-md-3 form-check">
                                <input type="radio" name="catalogo_reporte_faltante_tipo_id" value="1"
                                    class="form-check-input" {{ $tipoSeleccionado==1 ? 'checked' : '' }}
                                    id="tipo_procede">
                                <label class="form-check-label" for="tipo_No_procede">No Procede</label>
                            </div>

                            <div class="col-md-3 form-check">
                                <input type="radio" name="catalogo_reporte_faltante_tipo_id" value="2"
                                    class="form-check-input" {{ $tipoSeleccionado==2 ? 'checked' : '' }}
                                    id="tipo_No_procede">
                                <label class="form-check-label" for="tipo_procede">Procede</label>
                            </div>

                            <div class="col-md-3 form-check">
                                <input type="radio" name="catalogo_reporte_faltante_tipo_id" value="3"
                                    class="form-check-input" {{ $tipoSeleccionado==3 ? 'checked' : '' }}
                                    id="tipo_cambio_fisico">
                                <label class="form-check-label" for="tipo_cambio_fisico">Cambio Físico</label>
                            </div>

                            <div class="col-md-3 form-check">
                                <input type="radio" name="catalogo_reporte_faltante_tipo_id" value="4"
                                    class="form-check-input" {{ $tipoSeleccionado==4 ? 'checked' : '' }}
                                    id="tipo_nc_servicio">
                                <label class="form-check-label" for="tipo_nc_servicio">NC Servicio</label>
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-success btn-lg">Guardar Reporte</button>
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

    // Autocompletado Cliente
    $('#cliente').on('input', function(){
        let query = $(this).val().trim();
        if(!query || query.length < 2){
            $('#suggestions_cliente').empty().hide();
            $('#razon_social,#zona').val('');
            $('#catalogo_faltante_idcatalogo_faltante').val('');
            return;
        }
        $.get('{{ route("clientes.buscar") }}',{query}, function(data){
            let html = data.length ? data.map(item => `
                <div class="list-group-item list-group-item-action" role="button"
                    onclick="seleccionarCliente('${item.codigo}','${item.codigo_nombre}','${item.zona}','${item.idcatalogo_faltante}')">
                    ${item.codigo} - ${item.codigo_nombre}
                </div>`).join('') : '<div class="list-group-item">Sin coincidencias</div>';
            $('#suggestions_cliente').html(html).show();
        });
    });

    window.seleccionarCliente = function(codigo,nombre,zona,id){
        $('#cliente').val(codigo);
        $('#razon_social').val(nombre);
        $('#zona').val(zona);
        $('#catalogo_faltante_idcatalogo_faltante').val(id);
        $('#suggestions_cliente').empty().hide();
    };

    // Agregar/Clonar Factura
    let facturaIndex = 1;
    $('#add-factura').click(function(){
        let clone = $('.factura-item').first().clone();

        clone.find('input').each(function(){
            let name = $(this).attr('name');
            if(name) $(this).attr('name', name.replace(/\d+/, facturaIndex));
            if($(this).attr('id')) $(this).attr('id', $(this).attr('id').replace(/\d+/, facturaIndex));
            $(this).val('');
        });

        // Reset hidden catalogo_idcatalogo
        clone.find('input[name$="[catalogo_idcatalogo]"]').val('');

        clone.find('.remove-factura').show();
        $('#factura-wrapper').append(clone);
        facturaIndex++;
    });

    $(document).on('click','.remove-factura',function(){
        $(this).closest('.factura-item').remove();
    });

    // Autocompletado Factura
    $(document).on('input', 'input[name$="[factura]"]', function() {
        let input = $(this);
        let query = input.val().trim();
        let suggestions = input.siblings('.factura-suggestions');

        if(query.length < 2){ suggestions.empty().hide(); return; }

        // Obtener el cliente actual
        let clicod = $('#cliente').val().trim();

        if(!clicod){
            suggestions.html('<div class="list-group-item text-danger">Seleccione un cliente primero</div>').show();
            return;
        }

        $.get('{{ route("facturas.buscar") }}', { query, clicod }, function(data){
            let html = data.length ? data.map(item => `
                <div class="list-group-item list-group-item-action" role="button"
                    onclick='seleccionarFactura(${JSON.stringify(item)}, this)'>
                    ${item.dnum} -> <b>${item.icod}</b>
                </div>`).join('') : '<div class="list-group-item">Sin coincidencias</div>';
            suggestions.html(html).show();
        });
    });

window.seleccionarFactura = function(producto, inputEl) {
    let row = $(inputEl).closest('.factura-item');

    row.find('input[name$="[factura]"]').val(producto.dnum);
    row.find('input[name$="[descripcion]"]').val(producto.idescr);
    row.find('input[name$="[icod]"]').val(producto.icod);
    row.find('input[name$="[p_unitario]"]').val(producto.aiprecio);

    // Solo asigna cantidad si está vacío
    let cantidadInput = row.find('input[name$="[cantidad]"]');
    if(!cantidadInput.val()) {
        cantidadInput.val(producto.aicant);
    }

    // Actualiza hidden catalogo_idcatalogo con el nombre correcto
    row.find('input[name$="[catalogo_idcatalogo]"]').val(producto.idcatalogoproducto);

    row.find('.factura-suggestions').empty().hide();
};

});

</script>