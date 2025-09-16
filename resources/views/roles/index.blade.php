@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Roles y Permisos'])

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Roles y Permisos</h3>
        <a href="{{ route('roles.create') }}" class="btn btn-primary">Crear Rol / Permiso</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <h5>Roles</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Rol</th>
                        <th>Permisos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                        <tr>
                            <td>{{ $role->name }}</td>
                            <td>
                                @foreach($role->permissions as $perm)
                                    <span class="badge bg-info">{{ $perm->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar rol?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
