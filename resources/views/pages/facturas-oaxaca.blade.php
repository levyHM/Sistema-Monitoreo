@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@include('layouts.navbars.auth.topnav', ['title' => 'Facturas Oaxaca'])
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <h1 class="text-center">Control de Factura Oaxaca</h1>
                @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if (session('warning'))
                <div class="alert alert-warning">{{ session('warning') }}</div>
                @endif

                <!-- Contenedor para mostrar mensajes de Livewire -->
                <div id="resultado" class="mt-3"></div>
                <livewire:filter-pedidos-cdmx />

            </div>
        </div>
    </div>
    @include('layouts.footers.auth.footer')
</div>
<script>
    $('#updateButton').click(function() {
            $.ajax({
                url: '{{ route('copyData') }}',
                type: 'GET',
                success: function(response) {
                    $('body').prepend(
                        '<div class="alert alert-success text-center" role="alert"><strong>Exitoso: </strong>Copia Exitosa</div>'
                    );
                    location.reload(); // Recarga la página para actualizar los datos
                },
                error: function(xhr) {
                    $('body').prepend(
                        '  <div class="alert alert-danger  text-center" role="alert"><strong>Error</strong>Error en la Base datos</div>'
                    );
                }
            });
        });
</script>
@endsection