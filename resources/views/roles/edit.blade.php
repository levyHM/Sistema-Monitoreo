@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Editar Rol / Permiso'])

<div class="container-fluid py-4">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('roles.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="role_name" class="form-label">Nombre del Rol</label>
                    <input type="text" name="role_name" id="role_name" class="form-control" value="{{ $role->name }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Permisos</label>
                    <div class="form-check">
                        @foreach($permissions as $perm)
                            <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" id="perm-{{ $perm->id }}" class="form-check-input" {{ $role->hasPermissionTo($perm->name) ? 'checked' : '' }}>
                            <label for="perm-{{ $perm->id }}" class="form-check-label">{{ $perm->name }}</label><br>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection
