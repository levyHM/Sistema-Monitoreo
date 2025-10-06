@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Reporte de Faltante'])

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg border-0 p-4">
                {{-- Mensaje de éxito --}}
                @if (session('success'))
                <div class="alert alert-success text-center">
                    {{ session('success') }}
                </div>
                @endif
                {{-- Encabezado corporativo --}}
                <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                    <div>
                        <h4 class="mb-0 text-uppercase text-primary">JIGAFRA S.A. DE C.V.</h4>
                        <small class="text-muted">Reporte de Faltante CDMX</small>
                    </div>
                    <div class="text-end">
                        <h5 class="mb-0">Folio:
                            <span class="badge bg-danger">
                                {{ str_pad($reporte->idreporte_faltante ?? '', 5, '0', STR_PAD_LEFT) }}
                            </span>
                        </h5>
                        <h5 class="mb-0">Fecha: {{ \Carbon\Carbon::parse($reporte->fecha)->format('d/m/Y') }}</h5>
                    </div>
                </div>

                {{-- Reporte Cancelado --}}
                @if($reporte->estatus == '3')
                <div class="border border-danger rounded bg-white p-4 mb-4">
                    <h5 class="mb-3 text-uppercase text-danger fw-bold">❌ Reporte Cancelado</h5>
                    <p class="mb-0"><strong>Observaciones:</strong> {{ $reporte->observaciones ?? 'Sin observaciones
                        registradas.' }}</p>
                </div>
                @endif

                {{-- Datos generales --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="bg-light p-3 rounded border">
                            <strong>Cliente:</strong> {{ $reporte->catalogoFaltante->codigo ?? '' }}<br>
                            <strong>Razón Social:</strong> {{ $reporte->catalogoFaltante->codigo_nombre ?? '' }}<br>
                            <strong>Zona:</strong> {{ $reporte->catalogoFaltante->zona ?? '' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="bg-light p-3 rounded border">
                            <strong>Recibe Reporte:</strong> {{ $reporte->recibe_reporte ?? '' }}<br>
                            <strong>Autorizó:</strong> {{ $reporte->autorizo ?? 'JEFE DE ALMACÉN' }}
                        </div>
                    </div>
                </div>

                {{-- Tabla de productos --}}
                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-hover text-center align-middle">
                        <thead class="table-secondary">
                            <tr>
                                <th>Cantidad</th>
                                <th>No. Factura</th>
                                <th>No. Parte</th>
                                <th>Descripción</th>
                                <th>P. Unitario</th>
                                <th>Subtotal</th>
                                <th>Checó</th>
                                <th>Empacó</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            @foreach($reporte->reportesFalta as $factura)
                            @php
                            $precio_unitario = $factura->catalogoProducto->aiprecio ?? 0;
                            $cantidad = $factura->cantidad ?? 0;
                            $subtotal = $cantidad * $precio_unitario;
                            $total += $subtotal;
                            @endphp
                            <tr>
                                <td>{{ $cantidad }}</td>
                                <td>{{ $factura->numero_factura }}</td>
                                <td>{{ $factura->catalogoProducto->icod ?? '' }}</td>
                                <td>{{ $factura->catalogoProducto->idescr ?? '' }}</td>
                                <td>${{ number_format($precio_unitario, 2) }}</td>
                                <td>${{ number_format($subtotal, 2) }}</td>
                                <td>{{ $factura->checo }}</td>
                                <td>{{ $factura->empaco }}</td>
                            </tr>
                            @endforeach
                            <tr class="table-light">
                                <td colspan="5" class="text-end"><strong>Total:</strong></td>
                                <td colspan="3"><strong>${{ number_format($total, 2) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Motivo y solución --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="border p-3 rounded bg-white">
                            <strong class="text-muted">Motivo del Faltante:</strong>
                            <p class="mb-0">{{ $reporte->motivoFaltante->descripcion ?? '---' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border p-3 rounded bg-white">
                            <strong class="text-muted">Solución:</strong>
                            <p class="mb-0">{{ $reporte->solucion ?? '---' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Indicadores tipo checklist con radio buttons deshabilitados --}}
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input tipo-radio" type="radio"
                                name="catalogo_reporte_faltante_tipo_id" value="1" {{
                                $reporte->catalogo_reporte_faltante_tipo_id == 1 ? 'checked' : '' }} id="tipo_procede"
                            disabled>
                            <label class="form-check-label fw-bold" for="tipo_procede">Procede</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input tipo-radio" type="radio"
                                name="catalogo_reporte_faltante_tipo_id" value="2" {{
                                $reporte->catalogo_reporte_faltante_tipo_id == 2 ? 'checked' : '' }}
                            id="tipo_cambio_fisico" disabled>
                            <label class="form-check-label fw-bold" for="tipo_cambio_fisico">Cambio Físico</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input tipo-radio" type="radio"
                                name="catalogo_reporte_faltante_tipo_id" value="3" {{
                                $reporte->catalogo_reporte_faltante_tipo_id == 3 ? 'checked' : '' }}
                            id="tipo_nc_servicio" disabled>
                            <label class="form-check-label fw-bold" for="tipo_nc_servicio">NC por Servicio</label>
                        </div>
                    </div>
                </div>


                {{-- Firma --}}
                {{-- Firma --}}
                <div class="mt-5 text-center">
                    @if($reporte && $reporte->userAutorizo && $reporte->userAutorizo->signature)
                    <img src="{{ asset('storage/' . $reporte->userAutorizo->signature) }}" alt="Firma"
                        class="img-fluid rounded-circle mb-2" style="max-width: 80px; max-height: 80px;">
                    @endif

                    <div class="fw-bold mb-1">
                        {{ $reporte && $reporte->userAutorizo ? $reporte->userAutorizo->firstname . ' ' .
                        $reporte->userAutorizo->lastname : 'Pendiente' }}
                    </div>
                    <div class="mb-2">Jefe de Almacén</div>

                    <form action="{{ route('reporte.faltante.autorizacion', $reporte->idreporte_faltante) }}"
                        method="POST" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn bg-gradient-success mt-3" @cannot('firmas.Reporte Faltantes') disabled
                            @endcannot @if($reporte && $reporte->userAutorizo && $reporte->userAutorizo->signature)
                            disabled @endif>
                            ✍️ Firmar Reporte
                        </button>
                    </form>
                </div>


                {{-- Botones de acción --}}
                <div class="mt-4 text-center">
                    <a href="{{ route('reporte.faltante') }}" class="btn bg-gradient-info">
                        ⬅️ Volver al listado
                    </a>
                    <a href="{{ route('reporte.faltante.edit', $reporte->idreporte_faltante) }}"
                        class="btn bg-gradient-warning me-2">
                        ✏️ Editar Reporte
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection