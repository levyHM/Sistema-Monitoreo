@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Nueva Solución'])

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-body">
                    {{-- Tipo --}}
                    @php
                    $tipo = request('tipo', 1); // Por defecto Garantía
                    $tipoTexto = $tipo == 2 ? 'Devolución' : 'Garantía';
                    @endphp

                    
                    <h1 class="text-center mb-4">🛠️ Nueva Solución a clientes {{ $tipoTexto }}</h1>

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
                                    style="z-index:1000;"></div>
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

                        {{-- Tabla Facturas --}}
                        <h5 class="mt-4">🧾 Detalle de productos</h5>
                        <div id="factura-wrapper">
                            <div
                                class="factura-item card shadow-sm mb-3 border-start border-3 border-secondary position-relative bg-light p-3">
                                <div class="row g-3 align-items-end">
                                    <input type="hidden" name="catalogo_tipo_id" value="{{ $tipo }}">
                                    <div class="col-md-2">
                                        <label class="form-label">Factura</label>
                                        <div class="input-group">
                                            <input type="text" name="facturas[0][factura]" class="form-control factura"
                                                placeholder="Buscar factura" readonly>
                                            <span class="input-group-text buscar-factura">
                                                <i class="ni ni-zoom-split-in"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">Cantidad</label>
                                        <input type="number" name="facturas[0][cantidad]" class="form-control cantidad"
                                            value="1" min="1" step="1">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Código (ICOD)</label>
                                        <input type="text" name="facturas[0][icod]" class="form-control icod" readonly>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">Descripción</label>
                                        <input type="text" name="facturas[0][descripcion]"
                                            class="form-control descripcion" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">P. Unitario</label>
                                        <input type="number" step="0.01" name="facturas[0][p_unitario]"
                                            class="form-control p_unitario" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Total</label>
                                        <input type="number" step="0.01" name="facturas[0][total]"
                                            class="form-control total" readonly>
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

                        {{-- Estatus --}}
                        <h5 class="mt-4">📋 Estatus de Nota</h5>
                        <div class="row mb-3 align-items-center">
                            @foreach([1=>'Aprobado',2=>'No aprobado',3=>'En Recolección',4=>'En Almacén',5=>'En
                            Dictamen'] as $val => $label)
                            <div class="col-md-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="radio" id="estatus_{{ $val }}" name="estatus"
                                        value="{{ $val }}">
                                    <label class="form-check-label" for="estatus_{{ $val }}">{{ $label }}</label>
                                </div>
                            </div>
                            @endforeach
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

{{-- Modal Facturas --}}
<div class="modal fade" id="modalFacturas" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Seleccionar Factura</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="searchFacturaModal" class="form-control mb-3" placeholder="Buscar factura">
                <div class="card">
                    <div class="table-responsive">
                        <table id="tablaFacturas" class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>Factura</th>
                                    <th>ICOD</th>
                                    <th>Seleccionar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Se llena dinámicamente con JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Cliente Alert --}}
<div class="modal fade" id="modalClienteAlert" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-warning">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">⚠️ Atención</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p>Debes seleccionar un cliente antes de continuar.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){

    let filaActual = null;
    let facturaIndex = $('#factura-wrapper .factura-item').length;

    // ===== Cliente =====
    $('#cliente').on('input', function(){
        let q = $(this).val().trim();
        if(q.length < 2){ $('#suggestions_cliente').empty().hide(); return; }
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
    $('#add-factura').click(function(){
        let clone = $('.factura-item').first().clone();
        clone.find('input').each(function(){
            let name = $(this).attr('name');
            if(name) $(this).attr('name', name.replace(/\d+/, facturaIndex));
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

    // ===== Abrir modal para seleccionar factura =====
    $(document).on('click','.buscar-factura', function(){
        if(!$('#cliente').val().trim() || !$('#cliente_id').val()){
            $('#modalClienteAlert').modal('show'); 
            return;
        }
        filaActual = $(this).closest('.factura-item');
        $('#modalFacturas').modal('show');
        $('#searchFacturaModal').val(filaActual.find('.factura').val()).trigger('input');
    });

    // ===== Buscar facturas en modal =====
    $(document).on('input', '#searchFacturaModal', function() {
    buscarFacturas(1);
});

// Maneja clics en los botones del paginador
$(document).on('click', '.pagination a', function(e) {
    e.preventDefault();
    let page = $(this).attr('href').split('page=')[1];
    buscarFacturas(page);
});

function buscarFacturas(page = 1) {
    let query = $('#searchFacturaModal').val().trim();
    let clicod = $('#cliente').val().trim();

    if (!clicod || query.length < 1) {
        $('#tablaFacturas tbody').html('');
        $('.pagination').remove();
        return;
    }

    $.get('{{ route("soluciones.buscar.catalogo") }}', { query, clicod, page }, function(response) {
        let html = response.data.length
            ? response.data.map(f =>
                `<tr>
                    <td>${f.dnum}</td>
                    <td>${f.icod}</td>
                    <td>
                        <a href="#!" class="text-black font-weight-bold text-xs seleccionar-factura"
                            data-factura="${f.dnum}"
                            data-icod="${f.icod}"
                            data-descripcion="${f.idescr}"
                            data-punit="${f.aiprecio}"
                            data-id="${f.idCatalogoSolucionesClientes}">
                            Seleccionar
                        </a>
                    </td>
                </tr>`
              ).join('')
            : '<tr><td colspan="3" class="text-center">No se encontraron facturas</td></tr>';

        $('#tablaFacturas tbody').html(html);
        $('.pagination').remove(); // elimina la anterior
        $('#tablaFacturas').after(response.links); // agrega la nueva
    });
}

    // ===== Seleccionar factura =====
    $(document).on('click', '.seleccionar-factura', function(){
        filaActual.find('.factura').val($(this).data('factura'));
        filaActual.find('.icod').val($(this).data('icod'));
        filaActual.find('.descripcion').val($(this).data('descripcion'));
        filaActual.find('.p_unitario').val($(this).data('punit'));
        filaActual.find('.total').val((parseFloat(filaActual.find('.cantidad').val()||1)*parseFloat($(this).data('punit')||0)).toFixed(2));
        filaActual.find('.catalogo_idcatalogo').val($(this).data('id'));
        $('#modalFacturas').modal('hide');
        calcularTotales();
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
        $('.total').each(function(){ totalFacturas += parseFloat($(this).val())||0; });
        let descuento = parseFloat($('#descuento').val())||0;
        let subtotal = totalFacturas - descuento;
        let iva = subtotal*0.16;
        let totalCompleto = subtotal+iva;

        $('#total').val(totalFacturas.toFixed(2));
        $('#subtotal').val(subtotal.toFixed(2));
        $('#iva').val(iva.toFixed(2));
        $('#total_completo').val(totalCompleto.toFixed(2));
    }

    // ===== Validación final =====
    $('#form-soluciones').on('submit', function(e){
        if(!$('#cliente').val().trim() || !$('#cliente_id').val()){
            e.preventDefault();
            $('#modalClienteAlert').modal('show'); 
            return;
        }
        let valid = true;
        $('.factura-item').each(function(){
            if(!$(this).find('.catalogo_idcatalogo').val()){
                valid = false;
                $(this).addClass('border border-danger');
            } else $(this).removeClass('border border-danger');
        });
        if(!valid){ 
            e.preventDefault(); 
            alert('Selecciona un ICOD válido para todas las facturas antes de guardar.'); 
        }
    });

});
</script>
@endpush