@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Recibos de Devolución'])

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 shadow">
                <h1 class="text-center">🧾 Nuevo Recibo de Devolución </h1>

                @if (session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                <div class="alert alert-danger text-center">{{ session('error') }}</div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">📋 Detalles del Recibo</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('recibos.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Datos generales -->
                            <div class="mb-3">
                                <label for="provedores_idprovedores" class="form-label">Proveedor</label>
                                <select name="provedores_idprovedores" id="provedores_idprovedores" class="form-select" required>
                                    <option value="">Seleccione un proveedor</option>
                                    @foreach($proveedores as $proveedor)
                                    <option value="{{ $proveedor->idcatalogo_provedores }}">
                                        {{ $proveedor->prvcod }} - {{ $proveedor->prvnom }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="devolucion" name="devolucion" value="1" checked>
                                <label class="form-check-label" for="devolucion">Devolución</label>
                            </div>

                            <input type="hidden" name="sucursal" value="P">
                            <input type="hidden" name="tipo_recibo" value="D">
                            <input type="hidden" name="usuario" value="{{ auth()->user()->id }}">

                            <!-- Bloque de facturas -->
                            <hr>
                            <h5 class="mb-3 text-success">📦 Facturas asociadas</h5>

                            <div id="factura-wrapper">
                                <div class="factura-item card shadow-sm mb-3 border-start border-3 border-secondary position-relative bg-light">
                                    <div class="card-body">
                                        <div class="row g-3 align-items-end">
                                            <div class="col-md-2">
                                                <label class="form-label">Factura</label>
                                                <input type="text" name="facturas[0][factura]" class="form-control" required>
                                            </div>
                                            <div class="col-md-1">
                                                <label class="form-label">Cantidad</label>
                                                <input type="number" name="facturas[0][cantidad]" class="form-control" required>
                                            </div>
                                            <div class="col-md-1 position-relative">
                                                <label class="form-label">No Parte (ICOD)</label>
                                                <input class="form-control" type="search" name="facturas[0][no_parte]" id="no_parte_0" autocomplete="off">
                                                <div id="suggestions_0" class="list-group position-absolute w-100" style="z-index: 1000;"></div>
                                            </div>
                                            <div class="col-md-1" style="display: none;">
                                                <label class="form-label">Codigo Proveedor</label>
                                                <input type="text" name="facturas[0][codigo_proveedor]" class="form-control" disabled>
                                            </div>
                                            <div class="col-md-5">
                                                <label class="form-label">Descripción</label>
                                                <input type="text" name="facturas[0][descripcion]" class="form-control" disabled required>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Observaciones</label>
                                                <select name="facturas[0][observaciones]" class="form-select">
                                                    <option value="No Solicitado">No Solicitado</option>
                                                    <option value="Multiplo Incompleto">Multiplo Incompleto</option>
                                                    <option value="Mal Empacado">Mal Empacado</option>
                                                    <option value="Error de compras">Error de compras</option>
                                                    <option value="Empaque dañado">Empaque dañado</option>
                                                    <option value="Empaque roto">Empaque roto</option>
                                                    <option value="Etiqueta No Visible">Etiqueta No Visible</option>
                                                    <option value="Producto Maltratado">Producto Maltratado</option>
                                                    <option value="Etiqueta No Apta Para Su Venta">Etiqueta No Apta Para Su Venta</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="remove-factura text-danger position-absolute top-0 end-0 p-2" role="button" style="cursor: pointer;">
                                        🗑️
                                    </div>
                                </div>
                            </div>

                            <div class="text-end mb-4">
                                <button type="button" class="btn btn-outline-success" id="addFacturaRow">➕ Agregar factura</button>
                            </div>

                            <hr>
                            <h5 class="mb-3 text-success">📸 Evidencias</h5>

                            <!-- Bloque de evidencias -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">📦 Evidencias (máximo 3 imágenes)</label>
                                <div>
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="btn-select-images">
                                        <i class="fas fa-upload me-1"></i> Agregar imágenes
                                    </button>
                                </div>

                                <!-- Input oculto para cargar imágenes -->
                                <input type="file" id="image-input" name="evidencias[]" accept="image/*" multiple hidden>

                                <!-- Contenedor donde se muestran las miniaturas -->
                                <div id="image-upload-container" class="d-flex gap-2 flex-wrap mt-3"></div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">💾 Guardar Recibo</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@include('layouts.footers.auth.footer')

@push('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let facturaIndex = 1;

    $('#addFacturaRow').on('click', function () {
        const wrapper = $('#factura-wrapper');
        const row = `
            <div class="factura-item card shadow-sm mb-3 border-start border-3 border-secondary position-relative ${facturaIndex % 2 === 0 ? 'bg-light' : 'bg-white'}">
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-2">
                            <label class="form-label">Factura</label>
                            <input type="text" name="facturas[${facturaIndex}][factura]" class="form-control" required>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">Cantidad</label>
                            <input type="number" name="facturas[${facturaIndex}][cantidad]" class="form-control" required>
                        </div>
                        <div class="col-md-1 position-relative">
                            <label class="form-label">No Parte (ICOD)</label>
                            <input type="search" class="form-control" name="facturas[${facturaIndex}][no_parte]" id="no_parte_${facturaIndex}" autocomplete="off">
                            <div id="suggestions_${facturaIndex}" class="list-group position-absolute w-100" style="z-index: 1000;"></div>
                        </div>
                        <div class="col-md-1" style="display: none;">
                            <label class="form-label">Codigo Proveedor</label>
                            <input type="text" name="facturas[${facturaIndex}][codigo_proveedor]" class="form-control" disabled required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Descripción</label>
                            <input type="text" name="facturas[${facturaIndex}][descripcion]" class="form-control" disabled required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Observaciones</label>
                            <select name="facturas[${facturaIndex}][observaciones]" class="form-select">
                                    <option value="No Solicitado">No Solicitado</option>
                                    <option value="Multiplo Incompleto">Multiplo Incompleto</option>
                                    <option value="Mal Empacado">Mal Empacado</option>
                                    <option value="Error de compras">Error de compras</option>
                                    <option value="Empaque dañado">Empaque dañado</option>
                                    <option value="Empaque roto">Empaque roto</option>
                                    <option value="Etiqueta No Visible">Etiqueta No Visible</option>
                                    <option value="Producto Maltratado">Producto Maltratado</option>
                                    <option value="Etiqueta No Apta Para Su Venta">Etiqueta No Apta Para Su Venta</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="remove-factura text-danger position-absolute top-0 end-0 p-2" role="button" style="cursor: pointer;">
                    🗑️
                </div>
            </div>
        `;
        wrapper.append(row);
        facturaIndex++;
    });

    $(document).on('click', '.remove-factura', function () {
        $(this).closest('.factura-item').remove();
    });

    
// Autocompletado de ICOD
$(document).on('input', '[id^="no_parte_"]', function () {
    let input = $(this);
    let id = input.attr('id');
    let index = id.split('_')[2] || 0;
    let query = input.val();

    if (query.trim() === '') {
        let codigoProveedorInput = $('input[name="facturas[' + index + '][codigo_proveedor]"]');
        codigoProveedorInput.val('').prop('required', false).closest('.col-md-1').hide();
        $('input[name="facturas[' + index + '][descripcion]"]').val('');
        $('#suggestions_' + index).empty().hide();
        return;
    }

    if (query.length >= 2) {
        $.ajax({
            url: '{{ route("productos.buscar") }}',
            data: { icod: query },
            success: function (data) {
                let html = '';
                if (data.length > 0) {
                    data.forEach(item => {
                        html += `
                            <div class="list-group-item list-group-item-action" role="button"
                                onclick="seleccionarICOD('${item.icod}', '${item.icodprv ?? ''}', '${item.idescrip}', ${index})">
                                ${item.icod}
                            </div>`;
                    });
                } else {
                    html = `<div class="list-group-item">Sin coincidencias</div>`;
                }
                $('#suggestions_' + index).html(html).show();
            }
        });
    } else {
        $('#suggestions_' + index).empty().hide();
    }
});

// Selección de ICOD
function seleccionarICOD(icod, icodprv, descripcion, index) {
    $('#no_parte_' + index).val(icod);

    const codigoProveedorInput = $('input[name="facturas[' + index + '][codigo_proveedor]"]');

    if (icodprv !== null && icodprv.trim() !== '') {
        codigoProveedorInput.val(icodprv).prop('required', true).closest('.col-md-1').show();
    } else {
        codigoProveedorInput.val('').prop('required', false).closest('.col-md-1').hide();
    }

    $('input[name="facturas[' + index + '][descripcion]"]').val(descripcion);
    $('#suggestions_' + index).empty().hide();
}
window.seleccionarICOD = seleccionarICOD;

</script>
@endpush

@push('js')
<script>
    document.getElementById('btn-select-images').addEventListener('click', () => {
        document.getElementById('image-input').click();
    });

    const imageInput = document.getElementById('image-input');
    const container = document.getElementById('image-upload-container');
    let selectedFiles = [];

    imageInput.addEventListener('change', () => {
        const newFiles = Array.from(imageInput.files);

        newFiles.forEach(file => {
            const exists = selectedFiles.some(f => f.name === file.name && f.size === file.size);
            if (!exists && selectedFiles.length < 3) {
                selectedFiles.push(file);
            }
        });

        if (selectedFiles.length > 3) {
            alert('Solo puedes subir hasta 3 imágenes.');
            selectedFiles = selectedFiles.slice(0, 3);
        }

        updatePreview();
        updateInputFiles();
    });

    function updatePreview() {
        container.innerHTML = '';

        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.classList.add('position-relative', 'border');
                div.style.width = '120px';
                div.style.height = '120px';
                div.style.borderRadius = '8px';
                div.style.overflow = 'hidden';
                div.style.marginRight = '10px';

                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '100%';
                img.style.height = '100%';
                img.style.objectFit = 'cover';

                const btnRemove = document.createElement('button');
                btnRemove.innerHTML = '&times;';
                btnRemove.style.position = 'absolute';
                btnRemove.style.top = '5px';
                btnRemove.style.right = '5px';
                btnRemove.style.background = 'rgba(0,0,0,0.6)';
                btnRemove.style.color = 'white';
                btnRemove.style.border = 'none';
                btnRemove.style.borderRadius = '50%';
                btnRemove.style.width = '24px';
                btnRemove.style.height = '24px';
                btnRemove.style.cursor = 'pointer';

                btnRemove.addEventListener('click', () => {
                    selectedFiles.splice(index, 1);
                    updatePreview();
                    updateInputFiles();
                });

                div.appendChild(img);
                div.appendChild(btnRemove);
                container.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }

    function updateInputFiles() {
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach(file => dataTransfer.items.add(file));
        imageInput.files = dataTransfer.files;
    }
</script>
@endpush

@endsection
