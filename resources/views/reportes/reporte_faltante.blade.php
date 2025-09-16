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
                <h1 class="text-center">Reporte de Faltantes</h1>

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
                                    <td>{{ $reporte->motivo_faltante }}</td>
                                    <td>{{ $reporte->solucion }}</td>
                                    <td>{{ $reporte->fecha }}</td>
                                    <td class="align-middle text-center">
                                        @can('Reporte Faltantes.visualizar')
                                        <a href="{{ route('reporte.faltante.show', $reporte->idreporte_faltante) }}"
                                            class="me-2" title="Ver recibo">👁️</a>
                                        @endcan
                                        <a href="{{ route('pdf.reporte_faltante', $reporte->idreporte_faltante) }}" class="me-2" title="Descargar PDF">🧾</a>
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
                        <div class="mt-3">
                            {{ $reportes->links() }}
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