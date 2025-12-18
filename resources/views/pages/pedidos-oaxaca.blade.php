@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@include('layouts.navbars.auth.topnav', ['title' => 'Pedidos Oaxaca'])
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <h1 class="text-center">Control de Pedidos Oaxaca</h1>
                @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if (session('warning'))
                <div class="alert alert-warning text-center">{{ session('warning') }}</div>
                @endif
                <div class="text-center mt-4">
                    <button id="updateButton" class="btn btn-info btn-md">Actualizar Datos</button>
                </div>

                <livewire:filter-pedidos-oaxaca>
            </div>
        </div>
    </div>
    @include('layouts.footers.auth.footer')
</div>
<script>
    $('#updateButton').click(function() {
            $.ajax({
                url: '{{ route('copyDataPedidos') }}',
                type: 'GET',
                success: function(response) {
                    $('body').prepend(
                        '<div class="alert alert-success text-center" role="alert"><strong>Exitoso: </strong>Copia Exitosa</div>'
                    );
                    location.reload(); // Recarga la página para actualizar los datos
                },
                error: function(xhr) {
                    $('body').prepend(
                        '  <div class="alert alert-danger text-center" role="alert"><strong>Error</strong>Error en la Base datos</div>'
                    );
                }
            });
        });
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.querySelector("form");
            const input = document.querySelector("input[name='captura']");

            form.addEventListener("submit", function(event) {
                setTimeout(() => {
                    input.focus();
                }, 100); // Asegura que el foco se mantenga después de enviar
            });
        });

</script>
@endsection