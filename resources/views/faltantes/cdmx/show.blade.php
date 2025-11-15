@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Formato Faltante/Sobrante CDMX'])

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card p-4 mb-4 shadow rounded-3">
                <h3 class="text-center mb-4 text-success">Formato Faltante/Sobrante CDMX </h3>
                @if($recibo->estatus == 'cancelado')
                <hr>
                <h5 class="mb-3 text-danger">❌ Cancelado</h5>
                <div class="col-md-6">
                    <p><strong>Observaciones:</strong> {{ $recibo->observaciones }}</p>
                </div>
                @endif
                <hr>
                {{-- Información General --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p><strong>Folio:</strong> <span class="text-danger">{{ $recibo->idrecibos }}</span></p>
                        <p><strong>Fecha:</strong>{{ $recibo->fecha ?
                            \Carbon\Carbon::parse($recibo->fecha)->format('d/m/Y') : 'Sin fecha' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Codigo De Proveedor:</strong> {{ $recibo->proveedor->prvcod }}</p>
                        <p><strong>Razon Social:</strong> {{ $recibo->proveedor->prvnom }}</p>
                    </div>
                </div>

                {{-- Concepto --}}
                <div class="d-flex gap-4 mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="faltante" name="faltante" value="1" disabled
                            {{ $recibo->conceptosEstado->first()?->faltante == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="faltante">Faltante</label>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="sobrante" name="sobrante" value="1" disabled
                            {{ $recibo->conceptosEstado->first()?->sobrante == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="sobrante">Sobrante</label>
                    </div>
                </div>

                {{-- Detalle de Faltante --}}
                <div class="mb-4">
                    <h5 class="section-title">Detalle de Faltante</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered text-center">
                            <thead class="thead-light">
                                <tr>
                                    <th>Factura</th>
                                    <th>Cantidad</th>
                                    <th>No Parte</th>
                                    <th>Codigo Proveedor</th>
                                    <th>Descripción</th>
                                    <th>Facturado</th>
                                    <th>Fisico</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recibo->conceptos as $item)
                                <tr>
                                    <td>{{ $item->numero_factura }}</td>
                                    <td>{{ $item->cantidad }}</td>
                                    <td>{{ $item->producto->icod ?? '-' }}</td>
                                    <td>{{ $item->producto->codigo_proveedor ?? '-' }}</td>
                                    <td>{{ $item->producto->idescrip ?? '-' }}</td>
                                    <td>{{ $item->facturado }}</td>
                                    <td>{{ $item->fisico }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Evidencias --}}
                @if ($recibo->evidencias->isNotEmpty())
                <div class="mb-4">
                    <h5 class="section-title">Evidencias</h5>
                    <div class="row justify-content-center">
                        @foreach ($recibo->evidencias as $evidencia)
                        <div class="col-md-4 mb-3 text-center">
                            <img src="{{ asset('storage/' . $evidencia->url) }}" class="img-fluid img-thumbnail"
                                style="height: 600px; object-fit: contain; cursor: zoom-in;" alt="Evidencia"
                                onclick="this.style.height='auto'; this.style.maxHeight='90vh'; this.style.cursor='zoom-out';"
                                onmouseleave="this.style.height='600px'; this.style.cursor='zoom-in';">
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Firmas --}}
                <div class="row text-center">
                    @php
                    $areas = [
                    1 => 'Recepción',
                    2 => 'Soluciones',
                    3 => 'Almacen',
                    4 => 'Compras',
                    5 => 'Proveedor',
                    ];

                    $permisosFirma = [
                    2 => 'firmas.Soluciones',
                    3 => 'firmas.Almacen',
                    4 => 'firmas.Compras',
                    5 => 'firmas.Proveedor',
                    ];
                    @endphp

                    @foreach ($areas as $idArea => $nombreArea)
                    @php
                    $firma = $recibo->firmas->firstWhere('catalogo_firma_idcatalogo_firma', $idArea);
                    $yaFirmado = $firma !== null;
                    $permiso = $permisosFirma[$idArea] ?? null;
                    @endphp

                    <div class="col-6 col-md-2 mb-4 d-flex flex-column align-items-center">
                        {{-- Mostrar firma si existe --}}
                        @if($firma && $firma->usuario && $firma->usuario->signature)
                        <img src="{{ asset('storage/' . $firma->usuario->signature) }}" alt="Firma"
                            class="img-fluid rounded-circle mb-2" style="max-width: 80px; max-height: 80px;">
                        @endif

                        {{-- Nombre del firmante --}}
                        <div class="fw-bold mb-1">
                            {{ $firma && $firma->usuario ? $firma->usuario->firstname . ' ' . $firma->usuario->lastname
                            : 'Pendiente' }}
                        </div>

                        {{-- Área --}}
                        <div class="mb-2">{{ $nombreArea }}</div>

                        {{-- Botón firmar --}}
                        @if($permiso && auth()->user()->can($permiso) && !$yaFirmado)
                        <form method="POST" action="{{ route('firmas.firmar', ['recibo' => $recibo->idrecibos]) }}">
                            @csrf
                            <input type="hidden" name="catalogo_firma_idcatalogo_firma" value="{{ $idArea }}">
                            <button type="submit" class="btn bg-gradient-success btn-sm">Firmar</button>
                        </form>
                        @else
                        <button class="btn btn-secondary btn-sm" disabled>
                            {{ $yaFirmado ? 'Firmado' : 'Firmar' }}
                        </button>
                        @endif
                    </div>
                    @endforeach
                </div>

                {{-- Botón Volver --}}
                <div class="text-end">
                    <a href="{{ route('faltantes.cdmx.index') }}" class="btn bg-gradient-info">⬅️ Volver</a>
                    @can('Faltante Sobrante.editar')
                    <a href="{{ route('faltantes.cdmx.edit', $recibo->idrecibos) }}" class="btn bg-gradient-warning">✏️ Editar</a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    @include('layouts.footers.auth.footer')
</div>
@endsection