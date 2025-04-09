<div>
    <div class="text-center mt-4">
        <button id="updateButton" class="btn btn-info btn-md">Actualizar Datos</button>
    </div>
    <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
        <form action="{{ route('facturas.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-10">
                    <div class="form-group">
                        <input type="text" name="captura" class="form-control form-control-md mr-2"
                            placeholder="Validar Codigo de Barras">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-md w-100">Validar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="d-flex justify-content-center mt-4">
        <label class="me-3">
            <input type="checkbox" wire:model.lazy="estatusPendiente" {{ $estatusPendiente ? 'checked' : '' }}> Pendiente
        </label>
        <label class="me-3">
            <input type="checkbox" wire:model.lazy="estatusValidado" {{ $estatusValidado ? 'checked' : '' }}> Validado
        </label>
    </div>
    <div class="card-header pb-0">
        <h6>Datos Control de Factura Xalapa</h6>
    </div>
    <div class="card-body px-0 pt-0 pb-2">
        <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>DITIPMV</th>
                        <th>DNUM</th>
                        <th>DFECHA</th>
                        <th>CLICOD</th>
                        <th>DPAR1</th>
                        <th>DHORA</th>
                        <th>Captura</th>
                        <th>Estatus</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($facturas as $factura)
                    <tr>
                        <td>{{ $factura->id }}</td>
                        <td>{{ $factura->DITIPMV }}</td>
                        <td>{{ $factura->DNUM }}</td>
                        <td>{{ $factura->DFECHA }}</td>
                        <td>{{ $factura->CLICOD }}</td>
                        <td>{{ $factura->DPAR1 }}</td>
                        {{-- Determinar el rango de horario (Diurno / Nocturno) --}}
                        @php
                            $hora = date('H:i:s', strtotime($factura->DHORA)); // Convertir a formato 24h
                            if ($hora >= '08:00:00' && $hora <= '20:59:59') {
                                $claseHora = 'bg-gradient-info'; // Diurno
                            } else {
                                $claseHora = 'bg-gradient-secondary'; // Nocturno

                            }
                        @endphp

                        <td>
                            <span class="badge {{ $claseHora }}">{{ $factura->DHORA }} </span>
                        </td>                                    <td>{{ $factura->CAPTURA }}</td>
                        <td class="align-middle text-center text-sm">
                            @if ($factura->ESTATUS == 1)
                            <span class="badge rounded-pill badge-md bg-gradient-success">Validado</span>
                            @else
                            <span class="badge rounded-pill badge-md bg-gradient-warning">Pendiente</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- Paginación -->
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            {{ $facturas->appends(request()->query())->links() }}
        </ul>
    </nav>
    </div>
</div>
<!-- Escuchar evento de Livewire y actualizar la URL -->
<script>
    document.addEventListener('livewire:load', function () {
        Livewire.on('updateUrl', (url) => {
            // Actualizar la URL en la barra de direcciones
            window.history.pushState({}, '', url);
        });
    });
</script>
