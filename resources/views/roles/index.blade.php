@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Roles y Permisos'])

<div class="container-fluid py-4">
{{-- Header --}}
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="mb-0">🔐 Roles y Permisos</h3>
            <p class="text-sm text-muted mb-0">Administración de accesos del sistema</p>
        </div>
        <a href="{{ route('roles.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Nuevo Rol / Permiso
        </a>
    </div>
</div>

{{-- Alertas --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-1"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Card principal --}}
<div class="card shadow-sm">
    <div class="card-header bg-white border-0">
        <h5 class="mb-0">📋 Listado de Roles</h5>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width:20%">Rol</th>
                        <th>Permisos asignados</th>
                        <th style="width:20%" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                        <tr>
                            <td>
                                <span class="fw-bold text-dark">{{ $role->name }}</span>
                            </td>
                            <td>
                                @forelse($role->permissions as $perm)
                                    <span class="badge bg-gradient-info me-1 mb-1">{{ $perm->name }}</span>
                                @empty
                                    <span class="text-muted text-sm">Sin permisos asignados</span>
                                @endforelse
                            </td>
                            <td class="text-center">
                                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-outline-warning me-1">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('¿Eliminar este rol?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                <i class="fas fa-info-circle me-1"></i>
                                No existen roles registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
@endsection
