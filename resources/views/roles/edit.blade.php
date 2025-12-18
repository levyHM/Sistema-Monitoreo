@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Editar Rol / Permiso'])

<div class="container-fluid py-4">

```
{{-- Header --}}
<div class="row mb-4">
    <div class="col-12">
        <h3 class="mb-0">✏️ Editar Rol</h3>
        <p class="text-sm text-muted">Modifica el nombre del rol y asigna permisos</p>
    </div>
</div>

{{-- Card --}}
<div class="row">
    <div class="col-lg-8 col-md-10 mx-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0">🔐 Información del Rol</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('roles.update', $role->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Nombre del Rol --}}
                    <div class="mb-4">
                        <label for="role_name" class="form-label fw-bold">Nombre del Rol</label>
                        <input type="text"
                               name="role_name"
                               id="role_name"
                               class="form-control"
                               value="{{ $role->name }}"
                               placeholder="Ej. Administrador"
                               required>
                    </div>

                    {{-- Permisos --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold mb-2">Permisos asignados</label>

                        <div class="row">
                            @foreach($permissions as $perm)
                                <div class="col-md-6 col-lg-4 mb-2">
                                    <div class="form-check border rounded p-2">
                                        <input type="checkbox"
                                               name="permissions[]"
                                               value="{{ $perm->name }}"
                                               id="perm-{{ $perm->id }}"
                                               class="form-check-input"
                                               {{ $role->hasPermissionTo($perm->name) ? 'checked' : '' }}>

                                        <label for="perm-{{ $perm->id }}" class="form-check-label">
                                            {{ $perm->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Acciones --}}
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Cancelar
                        </a>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Actualizar Rol
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
```

</div>
@endsection
