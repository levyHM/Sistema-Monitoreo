@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Reporte de Soluciones'])

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
                {{-- Encabezado --}}
                <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                    <div>
                        <h4 class="mb-0 text-uppercase text-primary">JIGAFRA S.A. DE C.V.</h4>
                        <small class="text-muted"> Soluciónes a clientes</small>
                    </div>
                    <div class="text-end">
                        <h5 class="mb-0">Folio:
                            <span class="badge bg-danger">
                                {{ str_pad($reporte->idreporte_soluciones_clientes ?? '', 5, '0', STR_PAD_LEFT) }}
                            </span>
                        </h5>
                        <h5 class="mb-0">Fecha: {{ $reporte->fecha ?
                            \Carbon\Carbon::parse($reporte->fecha)->format('d/m/Y') : '-' }}</h5>
                    </div>
                </div>
                {{-- Reporte Cancelado --}}
                @if($reporte->estatus == '6')
                <div class="border border-danger rounded bg-white p-4 mb-4">
                    <h5 class="mb-3 text-uppercase text-danger fw-bold">❌ Reporte Cancelado</h5>
                    <p class="mb-0"><strong>Observaciones:</strong> {{ $reporte->observaciones ?? 'Sin observaciones
                        registradas.' }}</p>
                </div>
                @endif
                {{-- Datos del cliente --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="bg-light p-3 rounded border">
                            <strong>Cliente:</strong> {{ $reporte->cliente->clicod ?? '-' }}<br>
                            <strong>Razón Social:</strong> {{ $reporte->cliente->clinom ?? '-' }}<br>
                            <strong>Zona:</strong> {{ $reporte->cliente->clipar1 ?? '-' }}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="bg-light p-3 rounded border">
                            <strong>Devolución:</strong>
                            @if($reporte->catalogoTipo && $reporte->catalogoTipo->id === 1)
                            <span class="badge bg-success">Sí</span>
                            @else
                            <span class="badge bg-secondary">No</span>
                            @endif
                            <br>
                            <strong>Garantía:</strong>
                            @if($reporte->catalogoTipo && $reporte->catalogoTipo->id === 2)
                            <span class="badge bg-success">Sí</span>
                            @else
                            <span class="badge bg-secondary">No</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Tabla de facturas --}}
                <h5 class="mb-3">🧾 Detalle de Productos</h5>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-hover text-center align-middle">
                        <thead class="table-secondary">
                            <tr>
                                <th>No. Factura</th>
                                <th>Cantidad</th>
                                <th>Código</th>
                                <th>Descripción</th>
                                <th>Piramidal</th>
                                <th>P. Unitario</th>
                                <th>Total</th>
                                <th>Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalFacturas = 0; @endphp
                            @forelse($reporte->soluciones as $solucion)
                            @php
                            $catalogo = $solucion->catalogo;
                            $cantidad = $solucion->cantidad ?? 1;
                            $total = $solucion->total ?? 0;
                            $precio = $catalogo->aiprecio ?? 0;
                            $totalLinea = $cantidad * $precio;
                            $totalFacturas += $solucion->total

                            @endphp
                            <tr>
                                <td>{{ $solucion->factura ?? '-' }}</td>
                                <td>{{ $cantidad }}</td>
                                <td>{{ $catalogo->icod ?? '-' }}</td>
                                <td>{{ $catalogo->idescr ?? '-' }}</td>
                                <td>{{ number_format($total, 2) }}</td>
                                <td>${{ number_format($precio,2) }}</td>
                                <td>${{ number_format($totalLinea,2) }}</td>
                                <td>{{ $solucion->observaciones ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7">No hay productos registrados en este reporte.</td>
                            </tr>
                            @endforelse

                            {{-- Totales --}}
                            <tr class="table-light">
                                <td colspan="5" class="text-end"><strong>Total:</strong></td>
                                <td colspan="2"><strong>${{ number_format($totalFacturas, 2) }}</strong></td>
                            </tr>
                            <tr class="table-light">
                                <td colspan="5" class="text-end"><strong>Descuento:</strong></td>
                                <td colspan="2">% {{ number_format($reporte->descuento ?? 0, 2) }}</td>
                            </tr>
                            <tr class="table-light">
                                <td colspan="5" class="text-end"><strong>Subtotal:</strong></td>
                                <td colspan="2">${{ number_format($reporte->subtotal ?? $totalFacturas, 2) }}</td>
                            </tr>
                            <tr class="table-light">
                                <td colspan="5" class="text-end"><strong>I.V.A. (16%):</strong></td>
                                <td colspan="2">${{ number_format($reporte->iva ?? (($reporte->subtotal ??
                                    $totalFacturas)*0.16), 2) }}</td>
                            </tr>
                            <tr class="table-light">
                                <td colspan="5" class="text-end"><strong>Total Completo:</strong></td>
                                <td colspan="2">${{ number_format($reporte->total_completo ?? (($reporte->subtotal ??
                                    $totalFacturas)*1.16), 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                {{-- Firmas separadas: Cliente y Cobrador --}}
                <div class="row mt-5">
                    {{-- Cliente --}}
                    <div class="col-6 text-center">
                        <strong></strong><br>
                        <hr class="border border-success" style="height: 2px;">
                        <strong></strong><br>
                        <small class="text-muted">Cliente Nombre y Firma</small>
                    </div>

                    {{-- Cobrador --}}
                    <div class="col-6 text-center">
                        <strong>{{ $reporte->cliente->clipar1 ?? '-' }}</strong><br>
                        <hr class="border border-success" style="height: 2px;">
                        <strong>{{ $reporte->cliente->clinom ?? '-' }}</strong><br>
                        <small class="text-muted">Cobrador Nombre y Firma</small>
                    </div>
                </div>
                {{-- Firmas: Soluciones y Crédito --}}
                <div class="row text-center mt-5">
                    @php
                    $firmas = [
                    'firma_soluciones' => [
                    'label' => 'Soluciones',
                    'usuario' => $reporte->firmanteSoluciones,
                    'permiso' => 'firmas.Reporte Soluciones',
                    ],
                    'firma_credito' => [
                    'label' => 'Crédito y Cobranza',
                    'usuario' => $reporte->firmanteCredito,
                    'permiso' => 'firmas.Soluciones Credito',
                    ],
                    ];

                    @endphp

                    @foreach ($firmas as $campo => $config)
                    @php
                    $usuarioFirma = $config['usuario'];
                    $yaFirmado = !empty($usuarioFirma);
                    $permiso = $config['permiso'];
                    @endphp

                    <div class="col-12 col-md-6 mb-4 d-flex flex-column align-items-center">
                        {{-- Mostrar firma si existe --}}
                        @if($yaFirmado && $usuarioFirma->signature)
                        <img src="{{ asset('storage/' . $usuarioFirma->signature) }}" alt="Firma {{ $config['label'] }}"
                            class="img-fluid rounded-circle mb-2" style="max-width: 80px; max-height: 80px;">
                        @endif

                        {{-- Línea de firma simulada --}}
                        <hr class="border border-success w-75" style="height: 2px;">

                        {{-- Nombre del firmante o estado --}}
                        <strong class="mb-1">
                            {{ $yaFirmado ? $usuarioFirma->firstname . ' ' . $usuarioFirma->lastname : 'Pendiente' }}
                        </strong>

                        {{-- Área de firma --}}
                        <small class="text-muted mb-2">{{ $config['label'] }}</small>

                        {{-- Botón firmar --}}
                        @if(auth()->user()->can($permiso) && !$yaFirmado)
                        <form method="POST"
                            action="{{ route('soluciones.firmas', ['id' => $reporte->idreporte_soluciones_clientes]) }}">
                            @csrf
                            <input type="hidden" name="campo" value="{{ $campo }}">
                            <button type="submit" class="btn bg-gradient-success btn-sm">Firmar</button>
                        </form>
                        @else
                        <button class="btn bg-gradient-success btn-sm" disabled>
                            {{ $yaFirmado ? 'Firmado' : 'Firmar' }}
                        </button>
                        @endif
                    </div>
                    @endforeach
                </div>

                {{-- Botón volver y editar --}}
                <div class="mt-4 text-center">
                    <a href="{{ route('soluciones.index') }}" class="btn bg-gradient-info">⬅️ Volver al listado</a>
                    <a href="{{ route('soluciones.edit', $reporte->idreporte_soluciones_clientes) }}"
                        class="btn bg-gradient-warning ms-2">✏️ Editar</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection