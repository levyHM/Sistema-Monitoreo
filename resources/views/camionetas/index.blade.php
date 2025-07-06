@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@include('layouts.navbars.auth.topnav', ['title' => 'camionetas'])

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <h1 class="text-center">Camionetas</h1>

                @if (session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                <div class="alert alert-danger text-center">{{ session('error') }}</div>
                @endif

                <div class="text-center mt-4">
                    <button id="altaConductorButton" class="btn btn-info btn-md" data-toggle="modal" data-target="#altaConductorModal">Alta de Camionetas</button>
                </div>

                <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1"></div>
            </div>

            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>CLAVE</th>
                                <th>PLACAS</th>
                                <th>MARCA</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($camionetas as $camioneta)
                            <tr>
                                <td>{{ $camioneta->id }}</td>
                                <td>{{ $camioneta->clave }}</td>
                                <td>{{ $camioneta->placas }}</td>
                                <td>{{ $camioneta->marca }}</td>
                                <td class="align-middle">
                                    <a href="#" class="text-secondary font-weight-bold text-xs" data-toggle="modal" data-target="#editModal{{ $camioneta->id }}">Editar</a>
                                </td>
                                <td class="align-middle">
                                    <a href="#" class="text-danger font-weight-bold text-xs" data-toggle="modal" data-target="#deleteModal{{ $camioneta->id }}">Eliminar</a>
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

<!-- Modal para Alta de Camioneta -->
<div class="modal fade" id="altaConductorModal" tabindex="-1" role="dialog" aria-labelledby="altaConductorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="altaConductorModalLabel">Alta de Camioneta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('camionetas.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="clave">Clave</label>
                        <input type="text" id="clave" name="clave" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="placas">Placas</label>
                        <input type="text" id="placas" name="placas" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="marca">Marca</label>
                        <input type="text" id="marca" name="marca" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Guardar Camioneta</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Editar Camioneta -->
@foreach($camionetas as $camioneta)
<div class="modal fade" id="editModal{{ $camioneta->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $camioneta->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $camioneta->id }}">Editar Camioneta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('camionetas.update', $camioneta->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="clave">Clave</label>
                        <input type="text" id="clave" name="clave" class="form-control" value="{{ $camioneta->clave }}" required>
                    </div>

                    <div class="form-group">
                        <label for="placas">Placas</label>
                        <input type="text" id="placas" name="placas" class="form-control" value="{{ $camioneta->placas }}" required>
                    </div>

                    <div class="form-group">
                        <label for="marca">Marca</label>
                        <input type="text" id="marca" name="marca" class="form-control" value="{{ $camioneta->marca }}" required>
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
@endforeach

<!-- Modal para Eliminar Camioneta -->
@foreach($camionetas as $camioneta)
<div class="modal fade" id="deleteModal{{ $camioneta->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $camioneta->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Eliminar Camioneta</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('camionetas.destroy', $camioneta->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p class="text-center">¿Estás seguro de eliminar la camioneta con placas <strong>{{ $camioneta->placas }}</strong>?</p>
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

<script></script>
@endsection
