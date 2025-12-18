@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Dashboard'])

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg p-4" style="border-radius: 15px;">
                <div class="text-center">
                    <div style="font-size: 3.5rem;">👋</div>
                    <h1 class="fw-bold mb-1" id="saludo"></h1>
                    <h3 class="text-primary fw-semibold">{{ Auth::user()->firstname. ' ' . Auth::user()->lastname ?? 'Usuario' }}</h3>
                    <h4 id="hora-actual" class="text-muted mt-3"></h4>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.footers.auth.footer')
@endsection

@push('js')
<script>
    function actualizarHora() {
        const opciones = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
        document.getElementById('hora-actual').textContent = new Date().toLocaleTimeString('es-MX', opciones);
    }

    function saludoDinamico() {
        const hora = new Date().getHours();
        let saludo = '';
        if (hora < 12) saludo = 'Buenos días';
        else if (hora < 19) saludo = 'Buenas tardes';
        else saludo = 'Buenas noches';
        document.getElementById('saludo').textContent = saludo;
    }

    setInterval(actualizarHora, 1000);
    actualizarHora();
    saludoDinamico();
</script>
@endpush
