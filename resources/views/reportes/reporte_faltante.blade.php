@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')

{{-- Scripts (opcional si no están ya incluidos en tu layout) --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@include('layouts.navbars.auth.topnav', ['title' => 'Reporte de Faltantes'])

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <h1 class="text-center">Reporte de Faltante</h1>

                {{-- Alertas --}}
                @if (session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                <div class="alert alert-danger text-center">{{ session('error') }}</div>
                @endif

                {{-- Botón para crear --}}
                @can('Reporte Faltantes.crear')
                <div class="text-center mt-4">
                    <a href="{{ route('reporte.faltante.create') }}" class="btn btn-success">Crear Faltante</a>
                </div>
                @endcan
                <form method="GET" action="{{ route('reporte.faltante') }}" class="row g-3 px-4 pt-4">
                    <div class="col-md-4">
                        <label for="motivo_id" class="form-label">Motivo</label>
                        <select name="motivo_id" id="motivo_id" class="form-select">
                            <option value="">-- Todos --</option>
                            @foreach ($motivos as $motivo)
                            <option value="{{ $motivo->id }}" {{ request('motivo_id')==$motivo->id ? 'selected' : '' }}>
                                {{ $motivo->descripcion }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="catalogo_reporte_faltante_tipo_id" class="form-label">Solución</label>
                        <select name="catalogo_reporte_faltante_tipo_id" id="catalogo_reporte_faltante_tipo_id"
                            class="form-select">
                            <option value="">-- Todas --</option>
                            @foreach ($tipos as $tipo)
                            <option value="{{ $tipo->id }}" {{ request('catalogo_reporte_faltante_tipo_id')==$tipo->id ?
                                'selected' : '' }}>
                                {{ $tipo->nombre }}
                            </option>
                            @endforeach
                        </select>
                    </div>


                    <div class="col-md-4">
                        <label for="estatus" class="form-label">Estatus</label>
                        <select name="estatus" id="estatus" class="form-select">
                            <option value="">-- Todos --</option>
                            <option value="1" {{ request('estatus')=='1' ? 'selected' : '' }}>Activo</option>
                            <option value="2" {{ request('estatus')=='2' ? 'selected' : '' }}>Pendiente</option>
                            <option value="3" {{ request('estatus')=='3' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                    </div>

                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary me-2">Filtrar</button>
                        <a href="{{ route('reporte.faltante') }}" class="btn btn-outline-secondary">Limpiar filtros</a>
                    </div>
                </form>


                {{-- Tabla --}}
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Código</th>
                                    <th>Descripción</th>
                                    <th>Motivo</th>
                                    <th>Solución</th>
                                    <th>Estatus</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reportes as $reporte)
                                <tr>
                                    <td>{{ str_pad($reporte->idreporte_faltante, 5, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $reporte->catalogoFaltante->codigo ?? '' }}</td>
                                    <td>{{ $reporte->catalogoFaltante->codigo_nombre ?? '' }}</td>
                                    <td>{{ $reporte->motivoFaltante->descripcion ?? '' }}</td>
                                    <td>{{ $reporte->catalogoTipoFaltante->nombre ?? '' }}</td>
                                    <td>
                                        @if($reporte->estatus == 1)
                                        <span class="badge bg-success rounded-circle"
                                            style="width:20px; height:20px; display:inline-block;">&nbsp;</span>
                                        @elseif($reporte->estatus == 2)
                                        <span class="badge bg-warning rounded-circle"
                                            style="width:20px; height:20px; display:inline-block;">&nbsp;</span>
                                        @elseif($reporte->estatus == 3)
                                        <span class="badge bg-danger rounded-circle"
                                            style="width:20px; height:20px; display:inline-block;">&nbsp;</span>
                                        @else
                                        {{ $reporte->estatus }}
                                        @endif
                                    </td>
                                    <td>{{ $reporte->fecha }}</td>
                                    <td class="align-middle text-center">
                                        @can('Reporte Faltantes.visualizar')
                                        <a href="{{ route('reporte.faltante.show', $reporte->idreporte_faltante) }}"
                                            class="me-2" title="Ver recibo">👁️</a>
                                        @endcan
                                        <a href="{{ route('pdf.reporte_faltante', $reporte->idreporte_faltante) }}"
                                            class="me-2" title="Descargar PDF">🧾</a>
                                        @can('Reporte Faltantes.eliminar')
                                        <a href="#" class="text-danger me-2" data-bs-toggle="modal"
                                            data-bs-target="#cancelarDevolucionModal{{ $reporte->idreporte_faltante }}"
                                            title="Cancelar devolución">
                                            <i class="fas fa-times-circle"></i>
                                        </a>
                                        @endcan
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{-- Paginación --}}
                        <div class="text-center text-muted mb-2">
                            Mostrando {{ $reportes->firstItem() }} al {{ $reportes->lastItem() }} de {{
                            $reportes->total() }} resultados
                        </div>

                        <div class="mt-3 d-flex justify-content-center">
                            {{ $reportes->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>

                    </div>
                </div>
            </div>
            {{-- Modal de confirmación para cambiar de estatus de cancelación --}}
            @foreach($reportes as $reporte)
            <div class="modal fade" id="cancelarDevolucionModal{{ $reporte->idreporte_faltante }}" tabindex="-1"
                aria-labelledby="cancelarDevolucionLabel{{ $reporte->idreporte_faltante }}" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('reporte.faltante.cancelar', $reporte->idreporte_faltante) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title" id="cancelarDevolucionLabel{{ $reporte->idreporte_faltante }}">
                                    Cancelar Devolución #{{ $reporte->idreporte_faltante }}
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body">
                                <p>¿Estás seguro de que deseas cancelar esta devolución?</p>
                                <div class="mb-3">
                                    <label for="observaciones{{ $reporte->idreporte_faltante }}" class="form-label">
                                        Motivo / Observación:
                                    </label>
                                    <textarea class="form-control" name="observaciones"
                                        id="observaciones{{ $reporte->idreporte_faltante }}" rows="3"
                                        required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-danger">Confirmar Cancelación</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endforeach

            @include('layouts.footers.auth.footer')
        </div>
    </div>
</div>
@endsection