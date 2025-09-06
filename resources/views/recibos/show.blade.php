@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Detalle del Recibo'])

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card p-4 mb-4 shadow rounded-3">
                <h3 class="text-center mb-4 text-success">Recibo de Devolución</h3>
                {{-- Alertas --}}
                @if (session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                <div class="alert alert-danger text-center">{{ session('error') }}</div>
                @endif
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
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="devolucion" name="devolucion" value="1" disabled
                        {{ $recibo->conceptosEstado->first()?->devolucion === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="devolucion">Devolución</label>
                </div>

                {{-- Detalle de Devolución --}}
                <div class="mb-4">
                    <h5 class="section-title">Detalle de Devolución</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered text-center">
                            <thead class="thead-light">
                                <tr>
                                    <th>Factura</th>
                                    <th>Cantidad</th>
                                    <th>No Parte</th>
                                    <th>Codigo Proveedor</th>
                                    <th>Descripción</th>
                                    <th>Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recibo->conceptos as $item)
                                <tr>
                                    <td>{{ $item->numero_factura }}</td>
                                    <td>{{ $item->cantidad }}</td>
                                    <td>{{ $item->producto->icod ?? '-' }}</td>
                                    <td>{{ $item->producto->icodprv ?? '-' }}</td>
                                    <td>{{ $item->producto->idescrip ?? '-' }}</td>
                                    <td>{{ $item->observaciones }}</td>
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
                    1 => 'Recibo',
                    2 => 'Soluciones',
                    3 => 'Almacen',
                    4 => 'Compras',
                    5 => 'Proveedor'
                    ];
                    @endphp

                    @foreach ($areas as $idArea => $nombreArea)
                    @php
                    $firma = $recibo->firmas->firstWhere('catalogo_firma_idcatalogo_firma', $idArea);
                    $yaFirmado = $firma !== null;
                    $esMiRol = auth()->user()->hasRole($nombreArea) && auth()->user()->can('firmar');
                    @endphp

                    <div class="col-6 col-md-2 mb-4 d-flex flex-column align-items-center">
                        {{-- Firma --}}
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
                        @if($esMiRol)
                        <form method="POST" action="{{ route('firmas.firmar', ['recibo' => $recibo->idrecibos]) }}">
                            @csrf
                            <input type="hidden" name="catalogo_firma_idcatalogo_firma" value="{{ $idArea }}">
                            <button type="submit" class="btn btn-sm {{ $yaFirmado ? 'btn-primary' : 'btn-warning' }}" {{
                                $yaFirmado ? 'disabled' : '' }}>
                                Firmar
                            </button>
                        </form>
                        @else
                        <button class="btn btn-sm {{ $yaFirmado ? 'btn-warning' : 'btn-secondary' }}" disabled>
                            Firmar
                        </button>
                        @endif
                    </div>
                    @endforeach
                </div>

                {{-- Botón Volver --}}
                <div class="text-end">
                    <a href="{{ route('recibos.index') }}" class="btn btn-success me-2">⬅️ Volver</a>
                    @can('editar')
                    <a href="{{ route('recibos.edit', $recibo->idrecibos) }}" class="btn btn-info">✏️ Editar</a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    @include('layouts.footers.auth.footer')
</div>
@endsection