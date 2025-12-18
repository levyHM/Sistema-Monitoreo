@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Editar Faltante/Sobrante'])

<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
      <div class="card mb-4 shadow">
        <h1 class="text-center">✏️ Editar Faltante/Sobrante</h1>
        @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger text-center">{{ session('error') }}</div>
        @endif

        <div class="card">
          <div class="card-header">
            <h5>📋 Detalles del Faltante/Sobrante</h5>
          </div>
          <div class="card-body">
            <form action="{{ route('faltantes.cdmx.update', $recibo->idrecibos) }}" method="POST"
              enctype="multipart/form-data">
              @csrf
              @method('PUT')

              {{-- Proveedor --}}
              <div class="mb-3">
                <label>Proveedor</label>
                <select name="provedores_idprovedores" class="form-select" required>
                  <option value="">Seleccione un proveedor</option>
                  @foreach($proveedores as $prov)
                  <option value="{{ $prov->idcatalogo_provedores }}" {{ $prov->idcatalogo_provedores ==
                    $recibo->provedores_idprovedores ? 'selected' : '' }}>
                    {{ $prov->prvcod }} - {{ $prov->prvnom }}
                  </option>
                  @endforeach
                </select>
              </div>

              {{-- Devolución --}}
              <div class="d-flex gap-4 mb-3">
                {{-- Faltante --}}
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" id="faltante" name="faltante" value="1"  {{
                    optional($recibo->conceptosEstado->first())->faltante == 1 ? 'checked' : '' }}>
                  <label class="form-check-label" for="faltante">Faltante</label>
                </div>
                {{-- Sobrante --}}
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" id="sobrante" name="sobrante" value="1"  {{
                    optional($recibo->conceptosEstado->first())->sobrante == 1 ? 'checked' : '' }}>
                  <label class="form-check-label" for="sobrante">Sobrante</label>
                </div>
              </div>


              {{-- Ocultos --}}
              <input type="hidden" name="sucursal" value="{{ $recibo->sucursal }}">
              <input type="hidden" name="tipo_recibo" value="{{ $recibo->tipo_recibo }}">
              <input type="hidden" name="usuario" value="{{ $recibo->usuario }}">

              {{-- Facturas dinámicas --}}
              <hr>
              <h5>📦 Facturas asociadas</h5>
              <div id="factura-wrapper">
                @foreach($recibo->concepto_recibos as $i => $concepto)
                <div
                  class="factura-item card mb-3 border-start border-3 border-secondary p-3 position-relative {{ $i %2 ==0 ? 'bg-light' : 'bg-white' }}">
                  <div class="row gx-2 align-items-end">
                    <div class="col-md-2">
                      <label>Factura</label>
                      <input name="facturas[{{ $i }}][factura]" value="{{ $concepto->numero_factura }}" required
                        class="form-control">
                    </div>
                    <div class="col-md-1">
                      <label>Cantidad</label>
                      <input name="facturas[{{ $i }}][cantidad]" type="number" value="{{ $concepto->cantidad }}"
                        required class="form-control">
                    </div>
                    <div class="col-md-1 position-relative">
                      <label>No Parte</label>
                      <input id="no_parte_{{ $i }}" name="facturas[{{ $i }}][no_parte]"
                        value="{{ $concepto->producto->icod ?? '' }}" class="form-control">
                      <div id="suggestions_{{ $i }}" class="list-group position-absolute w-100" style="z-index:1000">
                      </div>
                    </div>
                    <div class="col-md-1">
                      <label>Numero de Parte</label>
                      <input name="facturas[{{ $i }}][no_parte]" value="{{ $concepto->producto->icodprv ?? '' }}"
                        class="form-control" disabled>
                    </div>
                    <div class="col-md-5">
                      <label>Descripción</label>
                      <input name="facturas[{{ $i }}][descripcion]" value="{{ $concepto->producto->idescrip ?? '' }}"
                        class="form-control" disabled>
                    </div>
                    <div class="col-md-1">
                      <label>Facturado</label>
                      <input name="facturas[{{ $i }}][facturado]" value="{{ $concepto->facturado ?? '' }}"
                        required class="form-control">
                    </div>
                    <div class="col-md-1">
                      <label>Físico</label>
                      <input name="facturas[{{ $i }}][fisico]"  value="{{ $concepto->fisico ?? '' }}"
                        required class="form-control">
                    </div>
                  </div>
                  <span class="remove-factura text-danger position-absolute top-0 end-0 p-2"
                    style="cursor:pointer;">🗑️</span>
                </div>
                @endforeach
              </div>
              <div class="text-end mb-4">
                <button type="button" id="addFacturaRow" class="btn btn-outline-success">➕ Agregar factura</button>
              </div>

              {{-- Evidencias --}}
              <hr>
              <h5>📸 Evidencias</h5>
              <div class="alert alert-warning">
                Al subir nuevas imágenes, se eliminarán las evidencias anteriores.
              </div>
              <div id="prev-evidencias" class="mb-3 d-flex flex-wrap gap-2">
                @foreach($recibo->evidencias as $img)
                <img src="{{ asset('storage/' . $img->url) }}" width="120" class="rounded shadow-sm">
                @endforeach
              </div>
              <div class="mb-3">
                <button type="button" id="btn-select-images" class="btn btn-outline-primary btn-sm">
                  <i class="fas fa-upload me-1"></i> Agregar imágenes
                </button>
                <input type="file" id="image-input" name="evidencias[]" accept="image/*" multiple hidden>
                <div id="image-upload-container" class="d-flex flex-wrap gap-2 mt-3"></div>
              </div>
              {{-- Botón --}}
              <div class="text-end">
                 <a href="{{ route('faltantes.cdmx.index') }}" class="btn btn-success">⬅️ Volver</a>
                <button type="submit" class="btn btn-warning">✏️ Actualizar Recibo</button>
              </div>
            </form>

            {{-- Templates JS --}}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@include('layouts.footers.auth.footer')
@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  let facturaIndex = {{ $recibo->concepto_recibos->count() }};

  $('#addFacturaRow').click(() => {
    const row = `
      <div class="factura-item card mb-3 border-start border-3 border-secondary p-3 position-relative ${facturaIndex%2==0?'bg-light':'bg-white'}">
        <div class="row gx-2 align-items-end">
          <div class="col-md-2"><label>Factura</label><input name="facturas[${facturaIndex}][factura]" required class="form-control"></div>
          <div class="col-md-1"><label>Cantidad</label><input name="facturas[${facturaIndex}][cantidad]" type="number" required class="form-control"></div>
          <div class="col-md-1 position-relative"><label>No Parte</label><input id="no_parte_${facturaIndex}" name="facturas[${facturaIndex}][no_parte]" class="form-control"><div id="suggestions_${facturaIndex}" class="list-group position-absolute w-100" style="z-index:1000"></div></div>
          <div class="col-md-6"><label>Descripción</label><input name="facturas[${facturaIndex}][descripcion]" disabled class="form-control"></div>
          <div class="col-md-1"><label>Facturado</label><input name="facturas[${facturaIndex}][facturado]"  required class="form-control"></div>
          <div class="col-md-1"><label>Físico</label><input name="facturas[${facturaIndex}][fisico]"  required class="form-control"></div>
        </div>
        <span class="remove-factura text-danger position-absolute top-0 end-0 p-2" style="cursor:pointer;">🗑️</span>
      </div>`;
    $('#factura-wrapper').append(row);
    facturaIndex++;
  });

  $(document).on('click', '.remove-factura', function() {
    $(this).closest('.factura-item').remove();
  });

  // Autocomplete ICOD
  $(document).on('input', '[id^="no_parte_"]', function(){
    let idx = $(this).attr('id').split('_')[2];
    let inp = $(this), val = inp.val();
    if(val.length<2) return $('#suggestions_'+idx).hide();
    $.get('{{ route("productos.buscar") }}', {icod: val}, d => {
      let html = d.length? d.map(i=>`<button type="button" class="list-group-item" onclick="seleccionarICOD('${i.icod}','${i.idescrip}',${idx})">${i.icod}</button>`).join(''):
               `<div class="list-group-item">Sin coincidencias</div>`;
      $('#suggestions_'+idx).html(html).show();
    });
  });

  window.seleccionarICOD = (icod, des, idx)=>{
    $('#no_parte_'+idx).val(icod);
    $(`input[name="facturas[${idx}][descripcion]"]`).val(des);
    $('#suggestions_'+idx).hide();
  };

  // Manejo de imagenes
  const imgInput = document.getElementById('image-input'),
        imgCont = document.getElementById('image-upload-container'),
        prevBox = document.getElementById('prev-evidencias');
  let selImgs = [];

  document.getElementById('btn-select-images').onclick = () => imgInput.click();
  imgInput.onchange = () => {
    const files = [...imgInput.files];
    if(files.length>3) return alert('Máximo 3 imágenes');
    selImgs = files; showImgs(); prevBox.classList.add('d-none');
  };

  function showImgs(){
    imgCont.innerHTML='';
    selImgs.forEach((file,i)=>{
      const fr=new FileReader();
      fr.onload = e => {
        let d = document.createElement('div');
        d.classList='position-relative border m-1';
        d.style='width:120px;height:120px;border-radius:8px;overflow:hidden';
        let img = document.createElement('img');
        img.src=e.target.result; img.style='width:100%;height:100%;object-fit:cover';
        let btn = document.createElement('button');
        btn.innerHTML='&times;'; btn.onclick=()=>{selImgs.splice(i,1); showImgs();};
        btn.style='position:absolute;top:5px;right:5px;background:rgba(0,0,0,0.6);color:#fff;border:none;width:24px;height:24px;border-radius:50%';
        d.append(img,btn);
        imgCont.appendChild(d);
      };
      fr.readAsDataURL(file);
    });
    const dt=new DataTransfer();
    selImgs.forEach(f=>dt.items.add(f));
    imgInput.files = dt.files;
  }
</script>
@endpush