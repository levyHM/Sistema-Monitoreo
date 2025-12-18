@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@include('layouts.navbars.auth.topnav', ['title' => 'Rutas'])

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <h1 class="text-center">Rutas</h1>

                @if (session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                <div class="alert alert-danger text-center">{{ session('error') }}</div>
                @endif

                <div class="text-center mt-4">
                    <button id="updateButton" class="btn btn-info btn-md">Actualizar Rutas</button>
                </div>

                <!-- Inicio de Formularios -->
                <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">

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
                                <th>CLAVE</th>
                                <th>OPERADOR</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rutas as $ruta)
                            <tr>
                                <td>{{ $ruta->agseq }}</td>
                                <td>{{ $ruta->agtnum }}</td>
                                <td>{{ $ruta->agdescr }}</td>
                                <td class="align-middle">
                                    <a href="#" class="text-secondary font-weight-bold text-xs" data-toggle="modal"
                                        data-target="#editModal{{ $ruta->id }}">
                                        Edit
                                    </a>
                                </td>
                                <td class="align-middle">
                                    <a href="#" class="text-danger font-weight-bold text-xs" data-toggle="modal"
                                        data-target="#deleteModal{{ $ruta->id }}">
                                        Eliminar
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.footers.auth.footer')

<!-- Modal para Alta de Conductor -->
<div class="modal fade" id="altaConductorModal" tabindex="-1" role="dialog" aria-labelledby="altaConductorModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="altaConductorModalLabel">Alta de Conductor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('conductores.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="clave">Clave</label>
                        <input type="text" id="clave" name="clave" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="operador">Operador</label>
                        <input type="text" id="operador" name="operador" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="observaciones">Observaciones</label>
                        <textarea id="observaciones" name="observaciones" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Guardar Conductor</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Editar Conductor -->
@foreach($rutas as $ruta)
<div class="modal fade" id="editModal{{ $ruta->id }}" tabindex="-1" role="dialog"
    aria-labelledby="editModalLabel{{ $ruta->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $ruta->id }}">Editar Conductor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('conductores.update', $ruta->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="clave">Clave</label>
                        <input type="text" id="clave" name="clave" class="form-control" value="{{ $ruta->clave }}"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="operador">Operador</label>
                        <input type="text" id="operador" name="operador" class="form-control"
                            value="{{ $ruta->operador }}" required>
                    </div>

                    <div class="form-group">
                        <label for="observaciones">Observaciones</label>
                        <textarea id="observaciones" name="observaciones"
                            class="form-control">{{ $ruta->observaciones }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal para Eliminar Conductor -->
<div class="modal fade" id="deleteModal{{ $ruta->id }}" tabindex="-1" role="dialog"
    aria-labelledby="deleteModalLabel{{ $ruta->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Eliminar Conductor</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('conductores.destroy', $ruta->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p class="text-center">¿Estás seguro de eliminar al conductor <strong>{{ $ruta->operador
                            }}</strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<script>
    $('#updateButton').click(function () {
        $.ajax({
            url: '{{ route('copyDataRutas') }}',
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