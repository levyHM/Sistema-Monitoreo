<div class="modal fade" id="modalEvidencias-{{ $solucion->idlista_soluciones_clientes }}" tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">

            {{-- Header con gradiente --}}
            <div class="modal-header text-white" style="background: linear-gradient(90deg, #02c40b, #0df06c);">
                <h5 class="modal-title fw-bold">
                    📂 Evidencias de la solución #{{ $solucion->idlista_soluciones_clientes }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                {{-- Metadatos --}}
                @if($solucion->evidencias->isNotEmpty())
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="border rounded p-3 bg-light">
                            <span class="d-block fw-semibold mb-1">📅 Fecha de operación</span>
                            <span class="text-muted">
                                {{ $solucion->fecha_operador
                                ? \Carbon\Carbon::parse($solucion->fecha_operador)->format('d/m/Y')
                                : '-' }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3 bg-light">
                            <span class="d-block fw-semibold mb-1">🛣️ Ruta</span>
                            <span class="text-muted">
                                {{ $solucion->ruta ?? '-' }}
                            </span>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Evidencias existentes --}}
                <h6 class="fw-bold mb-3">📸 Evidencias guardadas</h6>
                <div class="row">
                    @forelse($solucion->evidencias as $evidencia)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm border-0">

                            @php
                            $ruta = 'storage/' . $evidencia->archivo;
                            $extension = strtolower(pathinfo($evidencia->archivo, PATHINFO_EXTENSION));
                            $esImagen = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                            @endphp

                            {{-- Mostrar vista previa según tipo --}}
                            @if ($esImagen)
                            <img src="{{ asset($ruta) }}" class="card-img-top rounded"
                                style="max-height: 150px; object-fit: cover;">
                            @else
                            {{-- Vista previa para PDF --}}
                            <div class="d-flex justify-content-center align-items-center"
                                style="height:150px; background:#f8f9fa; border-radius:8px;">
                                <a href="{{ asset($ruta) }}" target="_blank" class="text-danger text-center">
                                    <i class="bi bi-file-earmark-pdf" style="font-size: 60px;"></i>
                                    <div>Ver PDF</div>
                                </a>
                            </div>
                            @endif

                            @can('solucionescliente.editar')
                            <div class="card-body text-center">
                                <form method="POST"
                                    action="{{ route('evidencias.destroy', [$solucion->idlista_soluciones_clientes, $evidencia->id]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm w-100">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </form>
                            </div>
                            @endcan
                        </div>

                    </div>
                    @empty
                    <p class="text-muted fst-italic">No hay evidencias registradas.</p>
                    @endforelse
                </div>

                {{-- Caso 1: Formulario SOLO si NO hay evidencias --}}
                @if($solucion->evidencias->isEmpty())
                <hr class="my-4">
                <h6 class="fw-bold mb-3">📤 Subir nuevas evidencias <span class="badge bg-info">máximo 3</span></h6>

                <form action="{{ route('evidencias.store', $solucion->idlista_soluciones_clientes) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Selecciona imágenes</label>
                        <input type="file" name="evidencias[]" class="form-control" accept="image/*,application/pdf"
                            multiple>
                        <div class="form-text">Puedes seleccionar varias imágenes a la vez.</div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">📅 Fecha de operación</label>
                            <input type="date" name="facturas[0][fecha_operador]" class="form-control" required
                                min="{{ date('Y-m-d') }}"
                                value="{{ old('facturas.0.fecha_operador', $solucion->fecha_operador ? \Carbon\Carbon::parse($solucion->fecha_operador)->format('Y-m-d') : '') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">🛣️ Ruta</label>
                            <input type="text" name="facturas[0][ruta]" class="form-control"
                                value="{{ old('facturas.0.ruta', $solucion->ruta) }}" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-4">
                        <i class="bi bi-upload"></i> Subir Evidencias
                    </button>
                </form>
                @endif

                {{-- Caso 2: Solo visualizar si hay evidencias y no tiene permiso --}}
                @if($solucion->evidencias->isNotEmpty())
                @cannot('solucionescliente.editar')
                <hr class="my-4">
                <p class="text-muted fst-italic">⚠️ Ya se han registrado evidencias para esta solución. Solo puedes
                    visualizarlas.</p>
                @endcannot
                @endif

                {{-- Caso 3: Editar si hay evidencias y tiene permiso --}}
                @can('solucionescliente.editar')
                @if($solucion->evidencias->isNotEmpty())
                <hr class="my-4">
                <h6 class="fw-bold mb-3">✏️ Editar solución <span class="badge bg-info">máximo 3</span></h6>

                <form action="{{ route('evidencias.update', $solucion->idlista_soluciones_clientes) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Actualizar imágenes</label>
                        <input type="file" name="evidencias[]" class="form-control" accept="image/*,application/pdf" multiple>
                        <div class="form-text">Puedes reemplazar o agregar nuevas imágenes (máximo 3 en total).</div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">📅 Fecha de operación</label>
                            <input type="date" name="facturas[0][fecha_operador]" class="form-control" required
                                min="{{ date('Y-m-d') }}"
                                value="{{ old('facturas.0.fecha_operador', $solucion->fecha_operador ? \Carbon\Carbon::parse($solucion->fecha_operador)->format('Y-m-d') : '') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">🛣️ Ruta</label>
                            <input type="text" name="facturas[0][ruta]" class="form-control"
                                value="{{ old('facturas.0.ruta', $solucion->ruta) }}" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success w-100 mt-4">
                        <i class="bi bi-pencil-square"></i> Guardar cambios
                    </button>
                </form>
                @endif
                @endcan

            </div>
        </div>
    </div>
</div>