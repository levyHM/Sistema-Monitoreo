<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/apple-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    <title>Dashboard - Control Monitoreo</title>

    <!-- Fonts and Icons -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <!-- Main CSS -->
    <link id="pagestyle" href="{{ asset('assets/css/argon-dashboard.css') }}" rel="stylesheet" />
    @livewireStyles
</head>

<body class="{{ $class ?? '' }}">

    @guest
    @yield('content')
    @endguest

    @auth
    @php
    $routeName = request()->route()->getName();
    @endphp

    @if (in_array($routeName, ['sign-in-static', 'sign-up-static', 'login', 'register', 'recover-password', 'rtl',
    'virtual-reality']))
    @yield('content')
    @else
    {{-- Fondo dinámico según ruta --}}
    @if (!in_array($routeName, ['profile', 'profile-static']))
    <div class="min-height-300 bg-success position-absolute w-100"></div>
    @elseif (in_array($routeName, ['profile-static', 'profile', 'dashboard']))
    <div class="position-absolute w-100 min-height-300 top-0"
        style="background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/profile-layout-header.jpg'); background-position-y: 50%;">
        <span class="mask bg-success opacity-6"></span>
    </div>
    @endif

    @include('layouts.navbars.auth.sidenav')

    <main class="main-content border-radius-lg">
        @yield('content')
    </main>

    {{-- Plugin opcional --}}
    {{-- @include('components.fixed-plugin') --}}
    @endif
    @endauth

    <!-- Core JS -->
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>

    <!-- Scrollbar init -->
    <script>
        if (navigator.platform.indexOf('Win') > -1 && document.querySelector('#sidenav-scrollbar')) {
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), { damping: '0.5' });
        }
    </script>

    <!-- GitHub Buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <!-- Dashboard JS -->
    <script src="{{ asset('assets/js/argon-dashboard.js') }}"></script>

    @stack('js')
    @livewireScripts
</body>

</html>