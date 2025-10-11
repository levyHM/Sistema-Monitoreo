<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 "
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="{{ route('home') }}" target="_blank">
            <img src="{{ asset('img/logo.png') }}" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold">Control Monitoreo</span>
        </a>
    </div>

    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'home' ? 'active' : '' }}"
                    href="{{ route('home') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>

            @can('dashboard.facturas')
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6" data-bs-toggle="collapse"
                    data-bs-target="#account-Facturas">
                    Facturas
                </h6>
            </li>
            <div class="collapse {{ str_contains(request()->url(), 'factura-') ? 'show' : '' }}" id="account-Facturas">
                @can('sucursales.cdmx')
                <li class="nav-item">
                    <a class="nav-link {{ str_contains(request()->url(), 'factura-cdmx') ? 'active' : '' }}"
                        href="{{ route('page', ['page' => 'factura-cdmx']) }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-building text-warning text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">CDMX</span>
                    </a>
                </li>
                @endcan
                @can('sucursales.oaxaca')
                <li class="nav-item">
                    <a class="nav-link {{ str_contains(request()->url(), 'factura-oaxaca') ? 'active' : '' }}"
                        href="{{ route('page', ['page' => 'factura-oaxaca']) }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-building text-warning text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Oaxaca</span>
                    </a>
                </li>
                @endcan
                @can('sucursales.xalapa')
                <li class="nav-item">
                    <a class="nav-link {{ str_contains(request()->url(), 'factura-xalapa') ? 'active' : '' }}"
                        href="{{ route('page', ['page' => 'factura-xalapa']) }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-building text-warning text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Xalapa</span>
                    </a>
                </li>
                @endcan
            </div>
            @endcan
            @can('dashboard.pedidos')
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6" data-bs-toggle="collapse"
                    data-bs-target="#account-Pedidos">
                    Pedidos
                </h6>
            </li>
            <div class="collapse {{ str_contains(request()->url(), 'pedidos-') ? 'show' : '' }}" id="account-Pedidos">
                @can('sucursales.cdmx')
                <li class="nav-item">
                    <a class="nav-link {{ str_contains(request()->url(), 'pedidos-cdmx') ? 'active' : '' }}"
                        href="{{ route('page', ['page' => 'pedidos-cdmx']) }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-app text-info text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">CDMX</span>
                    </a>
                </li>
                @endcan
                @can('sucursales.oaxaca')
                <li class="nav-item">
                    <a class="nav-link {{ str_contains(request()->url(), 'pedidos-oaxaca') ? 'active' : '' }}"
                        href="{{ route('page', ['page' => 'pedidos-oaxaca']) }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-app text-info text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Oaxaca</span>
                    </a>
                </li>
                @endcan
                @can('sucursales.xalapa')
                <li class="nav-item">
                    <a class="nav-link {{ str_contains(request()->url(), 'pedidos-xalapa') ? 'active' : '' }}"
                        href="{{ route('page', ['page' => 'pedidos-xalapa']) }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-app text-info text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Xalapa</span>
                    </a>
                </li>
                @endcan
            </div>
            @endcan

            @can('dashboard.embarques')
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6" data-bs-toggle="collapse"
                    data-bs-target="#account-collapse">
                    Control Embarques
                </h6>
            </li>
            <div class="collapse" id="account-collapse">
                @can('sucursales.cdmx')
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'embarque-cdmx' ? 'active' : '' }}"
                        href="{{ route('embarque-cdmx') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-credit-card text-success text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">CDMX</span>
                    </a>
                </li>
                @endcan
                @can('sucursales.xalapa')
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'embarque-xalapa' ? 'active' : '' }}" href="">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-credit-card text-success text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Xalapa</span>
                    </a>
                </li>
                @endcan
                @can('sucursales.oaxaca')
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'embarque-oaxaca' ? 'active' : '' }}" href="">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-credit-card text-success text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Oaxaca</span>
                    </a>
                </li>
                @endcan
            </div>
            @endcan
            @can('dashboard.embarques admin')
            <li class="nav-item mt-3 d-flex align-items-center">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6" data-bs-toggle="collapse"
                    data-bs-target="#account-administrador-embarques">
                    Administrador Embarques
                </h6>
            </li>
            <div class="collapse {{ str_contains(request()->url(), 'conductores-') ? 'show' : '' }}"
                id="account-administrador-embarques">
                <li class="nav-item">
                    <a class="nav-link {{ str_contains(request()->url(), 'factura-cdmx') ? 'active' : '' }}"
                        href="{{ route('page', ['page' => 'conductores']) }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-building text-warning text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Conductores</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_contains(request()->url(), 'camionetas-') ? 'active' : '' }}"
                        href="{{ route('page', ['page' => 'camionetas']) }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-building text-warning text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Camionetas</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_contains(request()->url(), 'rutas-') ? 'active' : '' }}"
                        href="{{ route('page', ['page' => 'rutas']) }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-building text-warning text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Rutas</span>
                    </a>
                </li>
            </div>
            @endcan
            <li class="nav-item mt-3 d-flex align-items-center">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6" data-bs-toggle="collapse"
                    data-bs-target="#account-administrador-recibos">
                    Recibos
                </h6>
            </li>
            <div class="collapse {{ str_contains(request()->url(), 'recibos-') ? 'show' : '' }}"
                id="account-administrador-recibos">
                @can('dashboard.recibos')
                <li class="nav-item">
                    <a class="nav-link {{ str_contains(request()->url(), 'recibos') ? 'active' : '' }}"
                        href="{{ route('page', ['page' => 'recibos']) }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-curved-next text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Devolución</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_contains(request()->url(), 'faltantes/cdmx') ? 'active' : '' }}"
                        href="{{ route('page', ['page' => 'faltantes/cdmx']) }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-chart-bar-32 text-danger text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Faltante/Sobrante</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_contains(request()->url(), 'reporte-faltante') ? 'active' : '' }}"
                        href="{{ route('page', ['page' => 'reporte-faltante']) }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-archive-2 text-primary text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Reporte Faltantes</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ str_contains(request()->url(), 'soluciones') ? 'active' : '' }}"
                        href="{{ route('page', ['page' => 'soluciones']) }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-single-02 text-success text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Error De Checado</span>
                    </a>
                </li>
                @endcan
            </div>
            <li class="nav-item mt-3 d-flex align-items-center">
                <div class="ps-4">
                    <i class="fab fa-laravel" style="color: #00953a"></i>
                </div>
                <h6 class="ms-2 text-uppercase text-xs font-weight-bolder opacity-6 mb-0">Administrador</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'profile' ? 'active' : '' }}"
                    href="{{ route('profile') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Perfil</span>
                </a>
            </li>
            @can('dashboard.usuarios')
            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'user-management') ? 'active' : '' }}"
                    href="{{ route('page', ['page' => 'user-management']) }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-bullet-list-67 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Usuarios</span>
                </a>
            </li>
            @endcan
            {{--
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'profile-static' ? 'active' : '' }}"
                    href="{{ route('profile-static') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Cuenta</span>
                </a>
            </li>
            --}}
        </ul>
</aside>