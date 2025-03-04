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

   

                <div class="text-center mt-4">
                    <button id="updateButton" class="btn btn-info btn-md">Actualizar Datos</button>
                </div>
                <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
                    <form action="{{ route('facturas.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <input type="text" name="captura" class="form-control form-control-md mr-2"
                                        placeholder="Validar Codigo de Barras">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success btn-md w-100">Validar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>


                <!-- Paginación -->

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
                        '<div class="alert alert-primary text-center" role="alert"><strong>Exitoso</strong>Copia Exitosa</div>'
                    );
                    location.reload(); // Recarga la página para actualizar los datos
                },
                error: function(xhr) {
                    $('body').prepend(
                        '  <div class="alert alert-danger" role="alert"><strong>Error</strong>Error en la Base datos</div>'
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