@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Registrar Usuario'])
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
                        Registrar Nuevo Usuario
                    </h5>
                    <p class="mb-0 font-weight-bold text-sm">
                        Control de Usuarios
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
                <form role="form" method="POST" action="{{ route('usuarios.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <p class="mb-0">Crear Usuario</p>
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
                                        value="{{ old('username') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-control-label">Correo electrónico</label>
                                    <input class="form-control" type="email" name="email" value="{{ old('email') }}"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="firstname" class="form-control-label">Nombre</label>
                                    <input class="form-control" type="text" name="firstname"
                                        value="{{ old('firstname') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lastname" class="form-control-label">Apellido</label>
                                    <input class="form-control" type="text" name="lastname"
                                        value="{{ old('lastname') }}">
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
                                        <option value="">Selecciona una sucursal</option>
                                        @foreach(\App\Models\CatalogoSucursal::all() as $sucursal)
                                        <option value="{{ $sucursal->id }}" {{
                                            old('catalogo_sucursales_id')==$sucursal->id ? 'selected' : '' }}>
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
                                    <input class="form-control" type="text" name="address" value="{{ old('address') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="city" class="form-control-label">Ciudad</label>
                                    <input class="form-control" type="text" name="city" value="{{ old('city') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="country" class="form-control-label">País</label>
                                    <input class="form-control" type="text" name="country" value="{{ old('country') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="postal" class="form-control-label">Código Postal</label>
                                    <input class="form-control" type="text" name="postal" value="{{ old('postal') }}">
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Contraseña -->
                        <h5 class="mb-3 text-dark">🔐 Contraseña</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="form-control-label">Contraseña</label>
                                    <input type="password" name="password" class="form-control form-control-lg"
                                        placeholder="Password" aria-label="Password">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation" class="form-control-label">Confirmar
                                        Contraseña</label>
                                    <input type="password" name="password_confirmation"
                                        class="form-control form-control-lg" placeholder="Confirm Password"
                                        aria-label="Confirm Password">
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h5 class="mb-3 text-dark">🧾 Acerca de mí</h5>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="about" class="form-control-label">Acerca de mí</label>
                                    <input class="form-control" type="text" name="about" value="{{ old('about') }}">
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