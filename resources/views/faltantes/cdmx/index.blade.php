@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')

{{-- Scripts (opcional si no están ya incluidos en tu layout) --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@include('layouts.navbars.auth.topnav', ['title' => 'Faltantes/Sobrantes'])

{{-- Contenido principal --}}

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <h1 class="text-center">Formato Faltante/Sobrante CDMX</h1>

                {{-- Alertas --}}
                @if (session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                <div class="alert alert-danger text-center">{{ session('error') }}</div>
                @endif

                {{-- Botón para crear --}}
                @can('Faltante.crear')
                <div class="text-center mt-4">
                    <a href="{{ route('faltantes.cdmx.create') }}" class="btn btn-success">Crear nuevo
                        faltante/sobrante</a>
                </div>
                @endcan

                {{-- Tabla --}}
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Código</th>
                                    <th>Factura</th>
                                    <th>Descripción</th>
                                    <th>Estatus</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recibos as $recibo)
                                <tr>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">
                                            {{ $recibo->sucursal }}{{ $recibo->tipo_recibo }} - {{ $recibo->idrecibos }}
                                        </p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ optional($recibo->proveedor)->prvcod
                                            }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">
                                            {{ optional($recibo->conceptos->first())->numero_factura }}
                                        </p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ optional($recibo->proveedor)->prvnom
                                            }}</p>
                                    </td>
                                    <td class="text-center">
                                        @if ($recibo->estatus == 'activo')
                                        <span class="badge rounded-pill badge-xs bg-gradient-success text-xs">{{
                                            $recibo->estatus }}</span>
                                        @else
                                        <span class="badge rounded-pill badge-xs bg-gradient-danger text-xs">{{
                                            ucfirst($recibo->estatus) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $recibo->fecha
                                            }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        @can('Faltante.visualizar')
                                        <a href="{{ route('faltantes.cdmx.show', $recibo->idrecibos) }}" class="me-2"
                                            title="Ver recibo">👁️</a>
                                        @endcan
                                        <a href="{{ route('pdf.faltantes', $recibo->idrecibos) }}" class="me-2"
                                            title="Descargar PDF">🧾</a>
                                        @can('Faltante.eliminar')
                                        <a href="#" class="text-danger me-2" data-bs-toggle="modal"
                                            data-bs-target="#cancelarDevolucionModal{{ $recibo->idrecibos }}"
                                            title="Cancelar devolución">
                                            <i class="fas fa-times-circle"></i>
                                        </a>
                                        @endcan
                                    </td>
                                </tr>

                                {{-- Modal único por recibo --}}
                                <div class="modal fade" id="cancelarDevolucionModal{{ $recibo->idrecibos }}"
                                    tabindex="-1" aria-labelledby="cancelarDevolucionLabel{{ $recibo->idrecibos }}"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form
                                            action="{{ route('faltantes.cdmx.faltantes.cancelar', $recibo->idrecibos) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-content">
                                                <div class="modal-header  text-white">
                                                    <h5 class="modal-title"
                                                        id="cancelarDevolucionLabel{{ $recibo->idrecibos }}">
                                                        Cancelar Devolución #{{ $recibo->idrecibos }}
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>¿Estás seguro de que deseas cancelar esta devolución?</p>
                                                    <div class="mb-3">
                                                        <label for="observaciones{{ $recibo->idrecibos }}"
                                                            class="form-label">Motivo / Observación:</label>
                                                        <textarea class="form-control" name="observaciones"
                                                            id="observaciones{{ $recibo->idrecibos }}" rows="3"
                                                            required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cerrar</button>
                                                    <button type="submit" class="btn btn-danger">Confirmar
                                                        Cancelación</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No hay recibos disponibles.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Paginación --}}
                <div class="text-center text-muted mb-2">
                    Mostrando {{ $recibos->firstItem() }} al {{ $recibos->lastItem() }} de {{ $recibos->total() }}
                    resultados
                </div>
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
                        {{ $recibos->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </ul>
                </nav>
            </div>

            @include('layouts.footers.auth.footer')
        </div>
    </div>
</div>

@endsection