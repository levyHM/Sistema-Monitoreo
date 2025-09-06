@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Editar Usuario'])
<div class="card shadow-lg mx-4 card-profile-bottom">
    <div class="card-body p-3">
        <div class="row gx-4">
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative">
                    <img src="/img/team-1.jpg" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
                </div>
            </div>
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1">
                        {{ $user->firstname ?? 'Firstname' }} {{ $user->lastname ?? 'Lastname' }}
                    </h5>
                    <p class="mb-0 font-weight-bold text-sm">
                        Public Relations
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="alert">
    @include('components.alert')
</div>
<div class="container-fluid py-4">
    @if (session('success'))
    <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger text-center">{{ session('error') }}</div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form role="form" method="POST" action="{{ route('usuarios.update', $user->id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <p class="mb-0">Editar Usuario</p>
                            <button type="submit" class="btn btn-primary btn-sm ms-auto">Guardar</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="mb-3 text-dark">👤 Información de Usuario</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="username" class="form-control-label">Username</label>
                                    <input class="form-control" type="text" name="username"
                                        value="{{ old('username', $user->username) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-control-label">Correo electrónico</label>
                                    <input class="form-control" type="email" name="email"
                                        value="{{ old('email', $user->email) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="firstname" class="form-control-label">Nombre</label>
                                    <input class="form-control" type="text" name="firstname"
                                        value="{{ old('firstname', $user->firstname) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lastname" class="form-control-label">Apellido</label>
                                    <input class="form-control" type="text" name="lastname"
                                        value="{{ old('lastname', $user->lastname) }}">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <!-- Sucursal -->
                        <h5 class="mb-3 text-dark">🏢 Sucursal</h5>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="catalogo_sucursales_id" class="form-control-label">Sucursal</label>
                                    <select class="form-control" name="catalogo_sucursales_id" required>
                                        @foreach(\App\Models\CatalogoSucursal::all() as $sucursal)
                                        <option value="{{ $sucursal->id }}" {{ old('catalogo_sucursales_id', $user->
                                            catalogo_sucursales_id) == $sucursal->id ? 'selected' : '' }}>
                                            {{ $sucursal->nombre }} 
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <!-- Información de Contacto -->
                        <h5 class="mb-3 text-dark">📍 Información de Contacto</h5>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="address" class="form-control-label">Dirección</label>
                                    <input class="form-control" type="text" name="address"
                                        value="{{ old('address', $user->address) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="city" class="form-control-label">Ciudad</label>
                                    <input class="form-control" type="text" name="city"
                                        value="{{ old('city', $user->city) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="country" class="form-control-label">País</label>
                                    <input class="form-control" type="text" name="country"
                                        value="{{ old('country', $user->country) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="postal" class="form-control-label">Código Postal</label>
                                    <input class="form-control" type="text" name="postal"
                                        value="{{ old('postal', $user->postal) }}">
                                </div>
                            </div>
                        </div>

                        <hr>
                        <!-- 🛡️ Roles -->
                        <h5 class="mb-3 text-dark">🛡️ Roles</h5>
                        <div class="mb-3">
                            @foreach($rolesGenerales as $role)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="roles[]" id="role-{{ $role->id }}"
                                    value="{{ $role->name }}" {{ in_array($role->name, $userRoles) ? 'checked' : '' }}>
                                <label class="form-check-label" for="role-{{ $role->id }}">{{ $role->name }}</label>
                            </div>
                            @endforeach
                            @foreach($rolesDashboard as $role)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="roles[]" id="role-{{ $role->id }}"
                                    value="{{ $role->name }}" {{ in_array($role->name, $userRoles) ? 'checked' : '' }}>
                                <label class="form-check-label" for="role-{{ $role->id }}">{{ $role->name }}</label>
                            </div>
                            @endforeach
                            @foreach($rolesSucursales as $role)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="roles[]" id="role-{{ $role->id }}"
                                    value="{{ $role->name }}" {{ in_array($role->name, $userRoles) ? 'checked' : '' }}>
                                <label class="form-check-label" for="role-{{ $role->id }}">{{ $role->name }}</label>
                            </div>
                            @endforeach
                        </div>
                        <hr>
                        <!-- ✍️ Permisos Firma -->
                        <h5 class="mt-4 mb-3 text-dark">✍️ Permisos Firma</h5>
                        <div class="mb-3">
                            @foreach($permisosFirma as $permission)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="permissions[]"
                                    id="permission-{{ $permission->id }}" value="{{ $permission->name }}" {{
                                    in_array($permission->name, $userPermissions) ? 'checked' : '' }}>
                                <label class="form-check-label" for="permission-{{ $permission->id }}">{{
                                    $permission->name }}</label>
                            </div>
                            @endforeach
                        </div>
                        <hr>
                        <!--  📊  Permisos Dashboard -->
                        <h5 class="mt-4 mb-3 text-dark">📊 Permisos Dashboard</h5>
                        <div class="mb-3">
                            @foreach($permisosDashboard as $permission)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="permissions[]"
                                    id="permission-{{ $permission->id }}" value="{{ $permission->name }}" {{
                                    in_array($permission->name, $userPermissions) ? 'checked' : '' }}>
                                <label class="form-check-label" for="permission-{{ $permission->id }}">{{
                                    $permission->name }}</label>
                            </div>
                            @endforeach
                        </div>
                        <hr>

                        <!-- 🏢 Permisos Sucursal -->
                        <h5 class="mt-4 mb-3 text-dark">🏢 Permisos Sucursal</h5>
                        <div class="mb-3">
                            @foreach($permisosSucursales as $permission)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="permissions[]"
                                    id="permission-{{ $permission->id }}" value="{{ $permission->name }}" {{
                                    in_array($permission->name, $userPermissions) ? 'checked' : '' }}>
                                <label class="form-check-label" for="permission-{{ $permission->id }}">{{
                                    $permission->name }}</label>
                            </div>
                            @endforeach
                        </div>
                        <hr>
                        <!-- 🖋️ Firma -->
                        <h5 class="mb-3 text-dark">🖋️ Firma</h5>
                        <div class="form-group">
                            <label for="signature">Subir Firma (imagen)</label>
                            <input type="file" class="form-control" name="signature" id="signature" accept="image/*">
                        </div>

                        @if($user->signature)
                        <p>Firma actual:</p>
                        <img src="{{ asset('storage/' . $user->signature) }}" alt="Firma" style="max-width:200px;">
                        @endif

                        <!-- 🧾 Acerca de mí -->
                        <h5 class="mb-3 text-dark">🧾 Acerca de mí</h5>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="about" class="form-control-label">Acerca de mí</label>
                                    <input class="form-control" type="text" name="about"
                                        value="{{ old('about', $user->about) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('layouts.footers.auth.footer')
</div>
@endsection