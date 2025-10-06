@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Nueva Solución'])

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h1 class="text-center mb-4">🛠️ Nueva Soluciónes a clientes</h1>

                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Ups...</strong> Corrige los siguientes errores:
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form id="form-soluciones" action="{{ route('soluciones.store') }}" method="POST">
                        @csrf

                        {{-- Cliente --}}
                        <div class="row mb-3 align-items-end">
                            <div class="col-md-4 position-relative">
                                <label class="form-label">Clave Cliente</label>
                                <input type="text" name="cliente" id="cliente" class="form-control" autocomplete="off"
                                    value="{{ old('cliente') }}">
                                <input type="hidden" name="catalogo_clientes_idcatalogo_clientes" id="cliente_id"
                                    value="{{ old('catalogo_clientes_idcatalogo_clientes') }}">
                                <div id="suggestions_cliente" class="list-group position-absolute w-100"
                                    style="z-index: 1000;"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Razón Social</label>
                                <input type="text" name="razon_social" id="razon_social" class="form-control"
                                    value="{{ old('razon_social') }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Colaborador</label>
                                <input type="text" name="colaborador" id="colaborador" class="form-control"
                                    value="{{ old('colaborador') }}" readonly>
                            </div>
                        </div>

                        {{-- Devolución y Garantía --}}
                        <div class="row mb-3 align-items-center">
                            <label class="form-label">Tipo</label>

                            <div class="col-md-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="radio" id="tipo_garantia"
                                        name="catalogo_tipo_id" value="1" {{ old('catalogo_tipo_id',
                                        $reporte->catalogo_tipo_id ?? '') == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="tipo_garantia">Garantía</label>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="radio" id="tipo_devolucion"
                                        name="catalogo_tipo_id" value="2" {{ old('catalogo_tipo_id',
                                        $reporte->catalogo_tipo_id ?? '') == 2 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="tipo_devolucion">Devolución</label>
                                </div>
                            </div>
                        </div>



                        {{-- Tabla Facturas --}}
                        <h5 class="mt-4">🧾 Detalle de productos</h5>
                        <div id="factura-wrapper">
                            <div
                                class="factura-item card shadow-sm mb-3 border-start border-3 border-secondary position-relative bg-light p-3">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-2">
                                        <label class="form-label">No. Factura</label>
                                        <input type="text" name="facturas[0][factura]" class="form-control" required>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">Cantidad</label>
                                        <input type="number" name="facturas[0][cantidad]" class="form-control cantidad"
                                            value="1" required min="1" step="1">
                                    </div>
                                    <div class="col-md-3 position-relative">
                                        <label class="form-label">Código (ICOD)</label>
                                        <input type="text" name="facturas[0][icod]" class="form-control icod"
                                            autocomplete="off">
                                        <div class="list-group position-absolute w-100 icod-suggestions"
                                            style="z-index:1000;"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Descripción</label>
                                        <input type="text" name="facturas[0][descripcion]"
                                            class="form-control descripcion" readonly required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">P. Unitario</label>
                                        <input type="number" step="0.01" name="facturas[0][p_unitario]"
                                            class="form-control p_unitario" readonly required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Total</label>
                                        <input type="number" step="0.01" name="facturas[0][total]"
                                            class="form-control total" readonly required>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label">Observaciones</label>
                                        <input type="text" name="facturas[0][observaciones]"
                                            class="form-control observaciones">
                                    </div>
                                    <input type="hidden" name="facturas[0][catalogo_idcatalogo]"
                                        class="catalogo_idcatalogo">
                                </div>
                                <div class="remove-factura text-danger position-absolute top-0 end-0 p-2" role="button"
                                    style="cursor:pointer; display:none;">🗑️</div>
                            </div>
                        </div>

                        <div class="text-end mb-3">
                            <button type="button" class="btn btn-outline-primary btn-sm" id="add-factura">➕ Agregar
                                Factura</button>
                        </div>
                        <h5 class="mt-4">📋 Estatus de Nota</h5>
                        {{-- Estatus (solo uno) --}}
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="radio" id="estatus_aprobado" name="estatus"
                                        value="1">
                                    <label class="form-check-label" for="estatus_aprobado">Aprobado</label>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="radio" id="estatus_no_aprobado" name="estatus"
                                        value="2">
                                    <label class="form-check-label" for="estatus_no_aprobado">No aprobado</label>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="radio" id="estatus_recoleccion" name="estatus"
                                        value="3">
                                    <label class="form-check-label" for="estatus_recoleccion">En Recolección</label>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="radio" id="estatus_almacen" name="estatus"
                                        value="4">
                                    <label class="form-check-label" for="estatus_almacen">En Almacén</label>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="radio" id="estatus_dictamen" name="estatus"
                                        value="5">
                                    <label class="form-check-label" for="estatus_dictamen">En Dictamen</label>
                                </div>
                            </div>
                        </div>


                        {{-- Totales --}}
                        <div class="row mb-3">
                            <div class="col-md-2 mb-2">
                                <label class="form-label">Total</label>
                                <input type="number" step="0.01" name="total" id="total" class="form-control" value="0"
                                    readonly>
                            </div>
                            <div class="col-md-2 mb-2">
                                <label class="form-label">Descuento</label>
                                <input type="number" name="descuento" id="descuento" class="form-control" value="0"
                                    readonly>
                            </div>
                            <div class="col-md-2 mb-2">
                                <label class="form-label">Subtotal</label>
                                <input type="number" step="0.01" name="subtotal" id="subtotal" class="form-control"
                                    value="0" readonly>
                            </div>
                            <div class="col-md-2 mb-2">
                                <label class="form-label">I.V.A. (16%)</label>
                                <input type="number" step="0.01" name="iva" id="iva" class="form-control" value="0"
                                    readonly>
                            </div>
                            <div class="col-md-2 mb-2">
                                <label class="form-label">Total Completo</label>
                                <input type="number" step="0.01" name="total_completo" id="total_completo"
                                    class="form-control" value="0" readonly>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-success">Guardar</button>
                            <a href="{{ route('soluciones.index') }}" class="btn btn-secondary">Cancelar</a>
                        </div>

                    </form>
                </div>
            </div>
            @include('layouts.footers.auth.footer')
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){

    // ===== Inicializar totales =====
    calcularTotales();

    // ===== Autocompletado Cliente =====
    $('#cliente').on('input', function(){
        let q = $(this).val().trim();
        if(q.length < 2){
            $('#suggestions_cliente').empty().hide();
            $('#razon_social,#colaborador,#cliente_id').val('');
            return;
        }
        $.get('{{ route("clientes.buscar.cliente") }}', { q }, function(data){
            let html = data.length
                ? data.map(item => `<div class="list-group-item list-group-item-action" role="button"
                    onclick="seleccionarCliente('${item.clicod}','${item.razon_social}','${item.clipar1}','${item.id}','${item.clidesc10}')">
                    ${item.clicod} - ${item.razon_social}</div>`).join('')
                : '<div class="list-group-item">Sin coincidencias</div>';
            $('#suggestions_cliente').html(html).show();
        });
    });

    window.seleccionarCliente = function(clicod, razon_social, colaborador, id, clidesc10){
        $('#cliente').val(clicod);
        $('#razon_social').val(razon_social);
        $('#colaborador').val(colaborador);
        $('#cliente_id').val(id);
        $('#descuento').val(clidesc10); 
        $('#suggestions_cliente').empty().hide();
    };

    $(document).on('click', function(e){
        if(!$(e.target).closest('#cliente,#suggestions_cliente').length){
            $('#suggestions_cliente').empty().hide();
        }
    });

    // ===== Agregar / remover factura =====
    let facturaIndex = $('#factura-wrapper .factura-item').length;
    $('#add-factura').click(function(){
        let clone = $('.factura-item').first().clone();
        clone.find('input').each(function(){
            let name = $(this).attr('name');
            if(name) $(this).attr('name', name.replace(/\d+/, facturaIndex));
            if($(this).attr('id')) $(this).attr('id', $(this).attr('id').replace(/\d+/, facturaIndex));
            if(!$(this).hasClass('catalogo_idcatalogo')) $(this).val('');
        });
        clone.find('.descripcion, .p_unitario, .total').prop('readonly', true);
        clone.find('.remove-factura').show();
        $('#factura-wrapper').append(clone);
        facturaIndex++;
        calcularTotales();
    });

    $(document).on('click','.remove-factura',function(){
        $(this).closest('.factura-item').remove();
        calcularTotales();
    });

    // ===== Autocompletado ICOD =====
    $(document).on('input', '.icod', function(){
        let input = $(this);
        let val = input.val().trim();
        let row = input.closest('.factura-item');
        let suggestions = input.siblings('.icod-suggestions');

        if(val.length < 1){
            row.find('.descripcion').val('');
            row.find('.p_unitario').val('');
            row.find('.total').val('');
            row.find('.observaciones').val('');
            row.find('.catalogo_idcatalogo').val('');
            suggestions.empty().hide();
            calcularTotales();
            return;
        }

        if(val.length < 2){ suggestions.empty().hide(); return; }

        $.get('{{ route("soluciones.buscar.catalogo") }}', { icod: val }, function(data){
            let html = data.length
                ? data.map(item => `<div class="list-group-item list-group-item-action" role="button"
                    onclick='seleccionarCatalogo(${JSON.stringify(item)}, this)'>
                    <strong>${item.icod}</strong> - ${item.idescr}</div>`).join('')
                : '<div class="list-group-item">Sin coincidencias</div>';
            suggestions.html(html).show();
        });
    });

    window.seleccionarCatalogo = function(item, el){
        let row = $(el).closest('.factura-item');
        row.find('.icod').val(item.icod);
        row.find('.descripcion').val(item.idescr);
        row.find('.p_unitario').val(item.aiprecio);
        row.find('.observaciones').val(item.observaciones || '');
        row.find('.total').val((parseFloat(row.find('.cantidad').val()||0) * parseFloat(item.aiprecio||0)).toFixed(2));
        row.find('.catalogo_idcatalogo').val(item.idCatalogoSolucionesClientes);
        row.find('.icod-suggestions').empty().hide();
        calcularTotales();
    };

    $(document).on('blur', '.icod', function(){
        let row = $(this).closest('.factura-item');
        if(!row.find('.catalogo_idcatalogo').val()){
            row.find('.icod').val('');
            row.find('.descripcion').val('');
            row.find('.p_unitario').val('');
            row.find('.total').val('');
        }
    });

    $(document).on('click', function(e){
        if(!$(e.target).closest('.icod,.icod-suggestions').length){
            $('.icod-suggestions').empty().hide();
        }
    });

    // ===== Calcular totales =====
    $(document).on('input','.cantidad, #descuento', function(){
        $('.factura-item').each(function(){
            let cantidad = parseFloat($(this).find('.cantidad').val()) || 0;
            let p_unitario = parseFloat($(this).find('.p_unitario').val()) || 0;
            $(this).find('.total').val((cantidad*p_unitario).toFixed(2));
        });
        calcularTotales();
    });

    function calcularTotales(){
        let totalFacturas = 0;
        $('.total').each(function(){ 
            totalFacturas += parseFloat($(this).val()) || 0; 
        });
        let descuento = parseInt($('#descuento').val()) || 0;
        let subtotal = totalFacturas - descuento;
        let iva = subtotal * 0.16;
        let totalCompleto = subtotal + iva;

        $('#total').val(totalFacturas.toFixed(2));
        $('#subtotal').val(subtotal.toFixed(2));
        $('#iva').val(iva.toFixed(2));
        $('#total_completo').val(totalCompleto.toFixed(2));
    }

    // ===== Validación final antes de enviar =====
    $('#form-soluciones').on('submit', function(e){
        let valid = true;
        $('.factura-item').each(function(){
            if(!$(this).find('.catalogo_idcatalogo').val()){
                valid = false;
                $(this).addClass('border border-danger');
            } else {
                $(this).removeClass('border border-danger');
            }
        });
        if(!valid){
            e.preventDefault();
            alert('Selecciona un ICOD válido para todas las facturas antes de guardar.');
        }
    });

});
</script>
@endpush