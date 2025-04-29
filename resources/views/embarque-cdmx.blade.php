@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

@include('layouts.navbars.auth.topnav', ['title' => 'embarque-cdmx'])

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <h1 class="text-center">Embarques CDMX</h1>

                @if (session('success'))
                    <div class="alert alert-success text-center">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger text-center">{{ session('error') }}</div>
                @endif

                <div class="text-center mt-4">
                    <button id="updateButton" class="btn btn-info btn-md">Actualizar Clientes</button>
                </div>

                <!-- Inicio de Formularios -->
                <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
                    <form action="{{ route('embarques.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <input type="text" name="ESCANER" class="form-control form-control-md mr-2"
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

                    <form action="{{ route('embarques.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="file">Selecciona el archivo Excel</label>
                            <input type="file" name="file" id="file" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Importar</button>
                    </form>
                </div>
                <!-- Fin de Formularios -->
            </div>

            <!-- Tabla -->
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>ESCANER</th>
                                <th>FACTURA</th>
                                <th>CLIENTE</th>
                                <th>SUCURSAL</th>
                                <th>CANTIDAD</th>
                                <th>VALIDACION</th>
                                <th>ESTATUS</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($embarques as $embarque)
                            <tr>
                                <td>{{ $embarque->id }}</td>
                                <td>{{ $embarque->ESCANER }}</td>
                                <td>{{ $embarque->FACTURA }}</td>
                                <td>{{ $embarque->CLIENTE }}</td>
                                <td>{{ $embarque->SUCURSAL }}</td>
                                <td>{{ $embarque->CANTIDAD }}</td>
                                <td>{{ $embarque->VALIDACION }}</td>
                                <td class="align-middle text-center text-sm">
                                    @if ($embarque->ESTATUS == 1)
                                        <span
                                            class="badge rounded-pill badge-md bg-gradient-success">Validado</span>
                                    @elseif ($embarque->ESTATUS == 3)
                                            <span class="badge rounded-pill badge-md bg-gradient-info">Paqueteria</span>        
                                    @else
                                        <span
                                            class="badge rounded-pill badge-md bg-gradient-warning">Pendiente</span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <a href="#" class="text-secondary font-weight-bold text-xs" data-toggle="modal"
                                        data-target="#editModal{{ $embarque->id }}">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ✅ Modales fuera del <table> -->
            @foreach ($embarques as $embarque)
            <div class="modal fade" id="editModal{{ $embarque->id }}" tabindex="-1" role="dialog"
                aria-labelledby="editModalLabel{{ $embarque->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <form action="{{ route('embarques.update', $embarque->id) }}" method="POST" class="modal-content">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel{{ $embarque->id }}">
                                Embarque ID {{ $embarque->id }}
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="FACTURA{{ $embarque->id }}">Factura</label>
                                <input type="text" class="form-control" name="FACTURA"
                                    id="FACTURA{{ $embarque->id }}" value="{{ $embarque->FACTURA }}">
                            </div>
                            <div class="form-group">
                                <label for="CLIENTE{{ $embarque->id }}">Cliente</label>
                                <input type="text" class="form-control" name="CLIENTE"
                                    id="CLIENTE{{ $embarque->id }}" value="{{ $embarque->CLIENTE }}">
                            </div>
                            <div class="form-group">
                                <label for="ESTATUS{{ $embarque->id }}">Estatus</label>
                                <input type="text" class="form-control" name="ESTATUS"
                                    id="ESTATUS{{ $embarque->id }}" value="{{ $embarque->ESTATUS }}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@include('layouts.footers.auth.footer')

<script>
    $('#updateButton').click(function () {
        $.ajax({
            url: '{{ route('copyDataClientes') }}',
            type: 'GET',
            success: function (response) {
                $('body').prepend(
                    '<div class="alert alert-primary text-center" role="alert"><strong>Exitoso:</strong> Copia Exitosa</div>'
                );
                location.reload();
            },
            error: function (xhr) {
                $('body').prepend(
                    '<div class="alert alert-danger text-center" role="alert"><strong>Error:</strong> Error en la Base de Datos</div>'
                );
            }
        });
    });
</script>
@endsection
