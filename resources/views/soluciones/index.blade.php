@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')

@include('layouts.navbars.auth.topnav', ['title' => 'Reporte de Soluciones'])

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <h1 class="text-center">Soluciónes a clientes</h1>

                {{-- Alertas --}}
                @if (session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                <div class="alert alert-danger text-center">{{ session('error') }}</div>
                @endif

                {{-- Botón para crear
                @can('Reporte Soluciones.crear')
                <div class="text-center mt-4">
                    <a href="{{ route('soluciones.create') }}" class="btn btn-success">Eliminar</a>
                </div>
                @endcan --}}

                {{-- Filtros --}}
                <div class="p-4 border-bottom">
                    <form method="GET" action="{{ route('soluciones.index') }}" class="row g-3 align-items-end">
                        <div class="col-md-2">
                            <label for="folio" class="form-label text-sm">Folio</label>
                            <input type="text" name="folio" id="folio" value="{{ request('folio') }}"
                                class="form-control" placeholder="Ej. 123">
                        </div>
                        <div class="col-md-2">
                            <label for="fecha" class="form-label text-sm">Fecha</label>
                            <input type="date" name="fecha" id="fecha" value="{{ request('fecha') }}"
                                class="form-control">
                        </div>

                        <div class="col-md-2">
                            <label for="codigo" class="form-label text-sm">Código Cliente</label>
                            <input type="text" name="codigo" id="codigo" value="{{ request('codigo') }}"
                                class="form-control" placeholder="Ej. C1234">
                        </div>
                        <div class="col-md-2">
                            <select name="catalogo_tipo_id" id="catalogo_tipo_id" class="form-select">
                                <option value="">Todos</option>
                                @foreach($tipos as $tipo)
                                <option value="{{ $tipo->id }}" {{ request('catalogo_tipo_id')==$tipo->id ? 'selected' :
                                    ''
                                    }}>
                                    {{ $tipo->nombre }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="estatus" class="form-label text-sm">Estatus</label>
                            <select name="estatus" id="estatus" class="form-select">
                                <option value="">Todos</option>
                                <option value="1" {{ request('estatus')=='1' ? 'selected' : '' }}>Aprobado</option>
                                <option value="2" {{ request('estatus')=='2' ? 'selected' : '' }}>No aprobado</option>
                                <option value="3" {{ request('estatus')=='3' ? 'selected' : '' }}>Cancelado</option>
                                <option value="4" {{ request('estatus')=='4' ? 'selected' : '' }}>En Ruta</option>
                                <option value="5" {{ request('estatus')=='5' ? 'selected' : '' }}>Almacen</option>
                                <option value="6" {{ request('estatus')=='6' ? 'selected' : '' }}>Pendiente</option>
                                <option value="7" {{ request('estatus')=='7' ? 'selected' : '' }}>En Dictamen</option>
                            </select>
                        </div>

                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary me-2">Filtrar</button>
                            <a href="{{ route('soluciones.index') }}" class="btn btn-outline-secondary">Limpiar
                                filtros</a>
                        </div>
                    </form>
                </div>

                {{-- Tabla --}}
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Fecha</th>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>Observaciones</th>
                                    <th>Estatus</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reportes as $reporte)
                                <tr>
                                    <td>
                                        {{ str_pad($reporte->idreporte_soluciones_clientes, 4, '0', STR_PAD_LEFT) }}{{
                                        $reporte->cliente->clipar1 ?? '' }}
                                    </td>
                                    </td>
                                    <td>{{ $reporte->fecha }}</td>
                                    <td>{{ $reporte->cliente->clicod ?? 'Sin nombre' }}</td>
                                    <td>{{ $reporte->cliente->clinom ?? 'Sin nombre' }}</td>
                                    <td>
                                        {{ $reporte->catalogoTipo->nombre }}
                                    </td>
                                    <td>
                                        <!-- Botón para abrir el modal de observaciones -->
                                        <button type="button" class="btn btn-link p-0" data-bs-toggle="modal"
                                            data-bs-target="#observacionesModal{{ $reporte->idreporte_soluciones_clientes }}">
                                            Ver Observaciones
                                        </button>

                                        <!-- Modal de Observaciones -->
                                        <div class="modal fade"
                                            id="observacionesModal{{ $reporte->idreporte_soluciones_clientes }}"
                                            tabindex="-1"
                                            aria-labelledby="observacionesModalLabel{{ $reporte->idreporte_soluciones_clientes }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content shadow-lg border-0 rounded-3">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="observacionesModalLabel{{ $reporte->idreporte_soluciones_clientes }}">
                                                            Observaciones</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Cerrar"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @if($reporte->soluciones && count($reporte->soluciones))
                                                        <div class="table-responsive">
                                                            <table class="table table-hover align-middle">
                                                                <thead class="table-secondary text-center">
                                                                    <tr>
                                                                        <th
                                                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                                            #</th>
                                                                        <th
                                                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                                            factura</th>
                                                                        <th
                                                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                                            Observaciones</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($reporte->soluciones as $index =>
                                                                    $solucion)
                                                                    <tr>
                                                                        <td class="text-center fw-semibold">{{ $index +
                                                                            1 }}</td>
                                                                        <td class="text-justify">
                                                                            {{$solucion->factura ?? '-' }}</td>
                                                                        <td class="text-justify">
                                                                            {{$solucion->observaciones ?? '-' }}</td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        @else
                                                        <p>Sin soluciones registradas.</p>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($reporte->estatus == 1)
                                        <span class="badge bg-gradient-success text-white">Aprobado</span>
                                        @elseif($reporte->estatus == 2)
                                        <span class="badge bg-gradient-danger text-white">No aprobado</span>
                                        @elseif($reporte->estatus == 3)
                                        <span class="badge bg-gradient-warning text-white">Cancelado</span>
                                        @elseif($reporte->estatus == 4)
                                        <span class="badge bg-gradient-info text-white">En Ruta</span>
                                        @elseif($reporte->estatus == 5)
                                        <span class="badge bg-gradient-primary text-white">Almacen</span>
                                        @elseif($reporte->estatus == 6)
                                        <span class="badge bg-gradient-secondary text-white">Pendiente</span>
                                        @elseif($reporte->estatus == 7)
                                        <span class="badge bg-gradient-dark text-white">En Dictamen</span>
                                        @else
                                        <span class="badge bg-secondary text-white">Desconocido</span>
                                        @endif
                                    </td>
                                    <td class="align-middle text-center">
                                        @can('solucionescliente.visualizar')
                                        <a href="{{ route('soluciones.show', $reporte->idreporte_soluciones_clientes) }}"
                                            class="me-2" title="Ver reporte">👁️</a>
                                        @endcan
                                        <a href="{{ route('pdf.reporte_soluciones_cliente', $reporte->idreporte_soluciones_clientes) }}"
                                            target="_blank" class="me-2" title="Descargar PDF">🧾</a>
                                        @can('solucionescliente.eliminar')
                                        <a href="#" class="text-danger me-2" data-bs-toggle="modal"
                                            data-bs-target="#cancelarReporteModal{{ $reporte->idreporte_soluciones_clientes }}"
                                            title="Cancelar reporte">
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
                            $reportes->total() }}
                            resultados
                        </div>
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-center">
                                {{ $reportes->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>

            {{-- Modal de cancelación --}}
            @foreach($reportes as $reporte)
            <div class="modal fade" id="cancelarReporteModal{{ $reporte->idreporte_soluciones_clientes }}" tabindex="-1"
                aria-labelledby="cancelarReporteLabel{{ $reporte->idreporte_soluciones_clientes }}" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('soluciones.cambiar.estatus', $reporte->idreporte_soluciones_clientes) }}"
                        method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header text-white">
                                <h5 class="modal-title"
                                    id="cancelarReporteLabel{{ $reporte->idreporte_soluciones_clientes }}">
                                    Cancelar Soluciones a Clientes #{{ $reporte->idreporte_soluciones_clientes }}
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body">
                                <p>¿Estás seguro de que deseas cancelar ?</p>
                                <div class="mb-3">
                                    <label for="observaciones{{ $reporte->idreporte_soluciones_clientes }}"
                                        class="form-label">
                                        Motivo / Observación:
                                    </label>
                                    <textarea class="form-control" name="observaciones"
                                        id="observaciones{{ $reporte->idreporte_soluciones_clientes }}" rows="3"
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