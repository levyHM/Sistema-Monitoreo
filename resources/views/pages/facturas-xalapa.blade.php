@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@include('layouts.navbars.auth.topnav', ['title' => 'Facturas Xalapa'])
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <h1 class="text-center">Control de Factura Xalapa</h1>
                @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if (session('warning'))
                <div class="alert alert-warning">{{ session('warning') }}</div>
                @endif

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
                        {{ $facturas->links('pagination::bootstrap-4') }}
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    @include('layouts.footers.auth.footer')
</div>
<script>
    $('#updateButton').click(function() {
            $.ajax({
                url: '{{ route('copyData') }}',
                type: 'GET',
                success: function(response) {
                    $('body').prepend(
                        '<div class="alert alert-primary text-center" role="alert"><strong>Exitoso</strong>Copia Exitosa</div>'
                    );
                    location.reload(); // Recarga la página para actualizar los datos
                },
                error: function(xhr) {
                    $('body').prepend(
                        '  <div class="alert alert-danger" role="alert"><strong>Error</strong>Error en la Base datos</div>'
                    );
                }
            });
        });
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.querySelector("form");
            const input = document.querySelector("input[name='captura']");

            form.addEventListener("submit", function(event) {
                setTimeout(() => {
                    input.focus();
                }, 100); // Asegura que el foco se mantenga después de enviar
            });
        });
</script>
@endsection