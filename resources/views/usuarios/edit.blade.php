@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Editar Usuario'])

<div class="container-fluid py-4">
    <div class="row">

        @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger text-center">{{ session('error') }}</div>
        @endif

        <div class="card shadow-lg">
            <form method="POST" action="{{ route('usuarios.update', $user->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0">Editar Usuario</h5>
                </div>
                <div class="card-body">

                    {{-- Información de Usuario --}}
                    <h5 class="mb-3 text-dark">👤 Información de Usuario</h5>
                    <div class="row">
                        @php
                        $userFields = [
                        ['label'=>'Username','name'=>'username','type'=>'text'],
                        ['label'=>'Correo electrónico','name'=>'email','type'=>'email'],
                        ['label'=>'Nombre','name'=>'firstname','type'=>'text'],
                        ['label'=>'Apellido','name'=>'lastname','type'=>'text'],
                        ['label'=>'Dirección','name'=>'address','type'=>'text'],
                        ['label'=>'Ciudad','name'=>'city','type'=>'text'],
                        ['label'=>'País','name'=>'country','type'=>'text'],
                        ['label'=>'Código Postal','name'=>'postal','type'=>'text'],
                        ['label'=>'Acerca de mí','name'=>'about','type'=>'text']
                        ];
                        @endphp

                        @foreach($userFields as $field)
                        <div class="col-md-6 mb-3">
                            <label for="{{ $field['name'] }}" class="form-control-label">{{ $field['label'] }}</label>
                            <input class="form-control" type="{{ $field['type'] }}" name="{{ $field['name'] }}"
                                value="{{ old($field['name'], $user->{$field['name']} ?? '') }}">
                        </div>
                        @endforeach
                    </div>

                    {{-- Sucursal --}}
                    <hr>
                    <h5 class="mb-3 text-dark">🏢 Sucursal</h5>
                    <select class="form-control mb-3" name="catalogo_sucursales_id" required>
                        @foreach(\App\Models\CatalogoSucursal::all() as $sucursal)
                        <option value="{{ $sucursal->id }}" {{ old('catalogo_sucursales_id', $user->
                            catalogo_sucursales_id)==$sucursal->id ? 'selected' : '' }}>
                            {{ $sucursal->nombre }}
                        </option>
                        @endforeach
                    </select>

                    {{-- Roles --}}
                    <hr>
                    <h5 class="mb-3 text-dark">🛡️ Roles</h5>
                    @foreach($rolesGrouped as $groupName => $roles)
                    <h6>{{ $groupName }}</h6>
                    <div class="mb-3">
                        @foreach($roles as $role)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input role-checkbox" type="checkbox" name="roles[]"
                                id="role-{{ $role->id }}" value="{{ $role->name }}"
                                data-permissions="{{ implode(',', $rolePermissionsMap[$role->name] ?? []) }}" {{
                                in_array($role->name, $userRoles) ? 'checked' : '' }}>
                            <label class="form-check-label" for="role-{{ $role->id }}">{{ $role->name }}</label>
                        </div>
                        @endforeach
                    </div>
                    @endforeach

                    {{-- Permisos Dinámicos --}}
                    <hr>
                    <h5 class="mb-3 text-dark">⚡ Permisos</h5>
                    @foreach($permissionsGrouped as $modulo => $acciones)
                    @php
                    $accionesArray = $acciones instanceof \Illuminate\Support\Collection ?
                    $acciones->pluck('name')->toArray() : (array)$acciones;
                    @endphp

                    <div class="card mb-3 shadow-sm">
                        <div class="card-header bg-secondary text-white">
                            <strong>{{ $modulo }}</strong>
                        </div>
                        <div class="card-body d-flex flex-wrap gap-2">
                            @foreach($accionesArray as $permName)
                            @php
                            $actionLabel = explode('.', $permName)[1] ?? $permName;
                            @endphp
                            <div class="form-check">
                                <input class="form-check-input perm-checkbox" type="checkbox" name="permissions[]"
                                    id="perm-{{ str_replace('.', '-', $permName) }}" value="{{ $permName }}" {{
                                    in_array($permName, $userPermissions) ? 'checked' : '' }}>
                                <label class="form-check-label" for="perm-{{ str_replace('.', '-', $permName) }}">
                                    {{ ucfirst($actionLabel) }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach

                    {{-- Firma --}}
                    <h5 class="mb-3 text-dark">🖋️ Firma</h5>
                    <div class="form-group">
                        <label for="signature">Subir Firma (imagen)</label>
                        <input type="file" class="form-control" name="signature" id="signature" accept="image/*">
                    </div>

                    @if($user->signature)
                    <p>Firma actual:</p>
                    <img src="{{ asset('storage/' . $user->signature) }}" alt="Firma" style="max-width:200px;">
                    @endif
                </div>
                <div class="card-header d-flex justify-content-center align-items-center">
                    <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('layouts.footers.auth.footer')

<script>
    // Activar permisos según el rol seleccionado
    document.querySelectorAll('.role-checkbox').forEach(function(roleCheckbox){
        roleCheckbox.addEventListener('change', function(){
            let checked = this.checked;
            let permissions = this.dataset.permissions?.split(',') || [];
            permissions.forEach(function(permName){
                let checkbox = document.querySelector(`.perm-checkbox[value="${permName}"]`);
                if (checkbox) checkbox.checked = checked;
            });
        });
    });
</script>
@endsection