@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    @include('layouts.navbars.auth.topnav', ['title' => 'Pedidos'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <h1 class="text-center">Control de Pedidos</h1>
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <div class="text-center mt-4">
                        <button id="updateButton" class="btn btn-info btn-md">Actualizar Datos</button>
                    </div>
                    <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
                        <form action="{{ route('pedidos.updateCaptura') }}" method="POST">
                            @csrf
                            @method('PATCH') <!-- Laravel requiere este método para actualizaciones -->
                            <div class="form-group">
                                <input type="text" name="captura" class="form-control form-control-md mr-2"
                                    placeholder="Validar Codigo de Barras">
                            </div>
                            <button type="submit" class="btn btn-success btn-md">Validar</button>
                        </form>
                    </div>
                    <div class="card-header pb-0">
                        <h6>Datos Control de Pedidos</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th>PESEQ</th>
                                        <th>PEFECHA</th>
                                        <th>PEDATE2</th>
                                        <th>PENUM</th>
                                        <th>PEALMACEN</th>
                                        <th>PEPAR0</th>
                                        <th>PEPAR1</th>
                                        <th>CAPTURA</th>
                                        <th>ESTATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pedidos as $pedido)
                                        <tr>
                                            <td>{{ $pedido->id }}</td> <!-- Asegúrate de que 'ID' sea un campo válido -->
                                            <td>{{ $pedido->PEFECHA }}</td>
                                            <td>{{ $pedido->PEDATE2 }}</td>
                                            <td>{{ $pedido->PENUM }}</td>
                                            <td>{{ $pedido->PEALMACEN }}</td>
                                            <td>{{ $pedido->PEPAR0 }}</td>
                                            <td>{{ $pedido->PEPAR1 }}</td>
                                            <td>{{ $pedido->CAPTURA }}</td>
                                            <td class="align-middle text-center text-sm">
                                                @if ($pedido->ESTATUS == 1)
                                                    <span
                                                        class="badge rounded-pill badge-md bg-gradient-success">Validado</span>
                                                @else
                                                    <span
                                                        class="badge rounded-pill badge-md bg-gradient-warning">Pendiente</span>
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
                            {{ $pedidos->links('pagination::bootstrap-4') }}
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
                url: '{{ route('copyDataPedidos') }}',
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
    </script>
@endsection
