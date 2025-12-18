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
                                    <input type="text" name="factura" class="form-control form-control-md mr-2"
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
                    <!-- Inicio Select -->

                    <form method="GET" action="{{ route('embarque-cdmx') }}">
                        <div class="col-md-3">
                            <label for="conductorSelect">Conductor</label>
                            <select class="form-control" id="conductorSelect" name="ID_OPERADOR"
                                onchange="this.form.submit()">
                                <option value="">Seleccione un conductor</option>
                                @foreach ($conductores as $conductor)
                                <option value="{{ $conductor->id }}" {{ request('ID_OPERADOR')==$conductor->id ?
                                    'selected' : '' }}>
                                    {{ $conductor->clave . ' - ' . $conductor->operador }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </form>

                    <!-- Fin Select -->
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

            <!-- Fin Tabla -->
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
                                <td>{{ $embarque->id_cliente }}</td>
                                <td>{{ $embarque->url_img }}</td>
                                <td>{{ $embarque->CANTIDAD }}</td>
                                <td>{{ $embarque->VALIDACION }}</td>
                                <td class="align-middle text-center text-sm">
                                    @if ($embarque->ESTATUS == 1)
                                    <span class="badge rounded-pill badge-md bg-gradient-success">Validado</span>
                                    @elseif ($embarque->ESTATUS == 3)
                                    <span class="badge rounded-pill badge-md bg-gradient-info">Paqueteria</span>
                                    @else
                                    <span class="badge rounded-pill badge-md bg-gradient-warning">Pendiente</span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <a href="#" class="text-secondary font-weight-bold text-xs" data-toggle="modal"
                                        data-target="#facturasModal{{ $embarque->id }}">
                                        Edit
                                    </a>
                                </td>
                                <td class="align-middle">
                                    <a href="#" class="text-secondary font-weight-bold text-xs" data-toggle="modal"
                                        data-target="#editModal{{ $embarque->id }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Fin Tabla -->
            <!-- ✅ Modal Embarques -->
            @foreach ($embarques as $embarque)
            <div class="modal fade" id="editModal{{ $embarque->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <form method="POST" class="modal-content">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Estatus del escáner {{ $embarque->ESCANER }}</h5>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Factura</th>
                                            <th>Estatus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($embarque->facturaPartes as $parte)
                                        <tr>
                                            <td>{{ $parte->factura }}-{{ $parte->parte_num }}</td>
                                            <td>
                                                <span
                                                    class="badge {{ $parte->estado_validacion == 1 ? 'bg-success' : 'bg-warning text-dark' }}">
                                                    {{ $parte->estado_validacion == 1 ? 'Validado' : 'Pendiente' }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
            @endforeach

            <!-- Fin Modal Embarques -->

            @foreach ($embarques as $embarque)
            <div class="modal fade" id="facturasModal{{ $embarque->id }}" tabindex="-1" role="dialog"
                aria-labelledby="facturasModalLabel{{ $embarque->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <form action="{{ route('embarques.update', $embarque->id) }}" method="POST" enctype="multipart/form-data" class="modal-content">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title" id="facturasModalLabel{{ $embarque->id }}">
                                Embarque {{ $embarque->id }}
                            </h5>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="FACTURA{{ $embarque->id }}">Factura</label>
                                <input type="text" class="form-control" name="FACTURA" id="FACTURA{{ $embarque->id }}"
                                    value="{{ $embarque->FACTURA }}" disabled>
                            </div>
                            <div class="form-group">
                                <label for="CONDUCTOR{{ $embarque->id }}">Conductor</label>
                                <select class="form-control" name="ID_OPERADOR" id="CONDUCTOR{{ $embarque->id }}"
                                    disabled>
                                    @foreach ($conductores as $conductor)
                                    <option value="{{ $conductor->id }}" {{ $embarque->id_conductor == $conductor->id ?
                                        'selected' : '' }}>
                                        {{ $conductor->clave . ' - ' . $conductor->operador }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="RUTA{{ $embarque->id }}">Ruta</label>
                                <select class="form-control" name="ID_RUTA" id="RUTA{{ $embarque->id }}" disabled>
                                    @foreach ($rutas as $ruta)
                                    <option value="{{ $ruta->id }}" {{ $embarque->ID_RUTA == $ruta->id ? 'selected' : ''
                                        }}>
                                        {{ $ruta->agseq . ' - ' . $ruta->agdescr }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="CAMIONETA{{ $embarque->id }}">Camioneta</label>
                                <select class="form-control" name="ID_CAMIONETA" id="CAMIONETA{{ $embarque->id }}"
                                    disabled>
                                    @foreach ($camionetas as $camioneta)
                                    <option value="{{ $camioneta->id }}" {{ $embarque->ID_CAMIONETA == $camioneta->id ?
                                        'selected' : '' }}>
                                        {{ $camioneta->clave . ' - ' . $camioneta->placas . ' - ' . $camioneta->marca }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="CLIENTE{{ $embarque->id }}">Cliente</label>
                                <input type="text" class="form-control" name="CLIENTE" id="CLIENTE{{ $embarque->id }}"
                                    value="{{ $embarque->id_cliente }}" disabled>
                            </div>
                            <div class="form-group">
                                <label for="archivoImagen{{ $embarque->id }}">Subir imagen</label>
                                <input type="file" class="form-control" name="archivoImagen"
                                    id="archivoImagen{{ $embarque->id }}" accept="image/*">
                            </div>
                            <div class="form-group">
                                <label for="exampleFormControlTextarea1">Observaciones</label>
                                <textarea class="form-control" name="observaciones" id="observaciones"  rows="3">{{ $embarque->OBSERVACIONES }}</textarea>
                            </div>
                            <div class="form-group">
                                <div class="card">
                                    <div class="table-responsive">
                                        <table class="table align-items-center mb-0">
                                            <thead>
                                                <tr>
                                                    <th
                                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        Factura</th>
                                                    <th
                                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                        Estatus</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($embarque->facturaPartes as $parte)
                                                <tr>
                                                    <td class="text-xs font-weight-bold mb-0">{{ $parte->factura }}-{{
                                                        $parte->parte_num }}</td>
                                                    <td class="text-xs">
                                                        @if($parte->estado_validacion == 1)
                                                        <span class="badge bg-success">Validado</span>
                                                        @else
                                                        <span class="badge bg-warning text-dark">Pendiente</span>
                                                        @endif
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
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

            <!-- Modal Facturas partes -->
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