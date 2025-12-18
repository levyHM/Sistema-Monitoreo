@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Crear Rol / Permiso'])

<div class="container-fluid py-4">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('permissions.update', $permission->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre del Permiso</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $permission->name) }}"
                        required>
                </div>

                <button type="submit" class="btn btn-success">Actualizar</button>
                <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection