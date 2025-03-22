@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@include('layouts.navbars.auth.topnav', ['title' => 'embarque-cdmx'])
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <h1 class="text-center">Embarques CDMX</h1>
                @if (session('success'))
                <div class="alert alert-success  text-center">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                <div class="alert alert-danger  text-center">{{ session('error') }}</div>
                @endif
                <div class="text-center mt-4">
                    <button id="updateButton" class="btn btn-info btn-md">Actualizar Clientes</button>
                </div>
                <!-- Inicio de Fomulario -->
                <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
                    <form>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="escanerInput" placeholder="Escaner">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <input class="form-control" type="number" value="1" id="id-label-total">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="idFactura" disabled>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" id="clienteInput" disabled>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="idProvedor" disabled>
                            </div>
                            <div class="text-center mt-4">
                                <button id="embarqueButton" class="btn btn-info btn-md">Registrar</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Fin de Fomulario -->

                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>ESCANER</th>
                                    <th>FACTURA</th>
                                    <th>CAJA</th>
                                    <th>CLIENTE</th>
                                    <th>SURCURSAL</th>
                                    <th>OPERADOR</th>
                                    <th>RUTA</th>
                                    <th>FECHA</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.footers.auth.footer')
</div>
<script>
    $('#updateButton').click(function() {
            $.ajax({
                url: '{{ route('copyDataClientes') }}',
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
        

        document.getElementById('escanerInput').addEventListener('blur', function () {
        const codigo = this.value;

        if (codigo.length > 11) {
            fetch(`/clientes/obtener?codigo=${codigo}`)
                .then(response => response.json())
                .then(data => {
                    const clienteInfo = document.getElementById('clienteInput');
                    const idProvedor = document.getElementById('idProvedor'); // Campo de la sucursal

                    
                    if (data.error) {
                        clienteInfo.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
                        idProvedor.value = ''; // Limpia el campo si hay un error
                    } else {
                        clienteInfo.innerHTML = `
                            <div class="alert alert-success">
                                Cliente encontrado: <strong>${data.CLICOD}</strong> - ${data.nombre}
                            </div>
                        `;
                        clienteInfo.value = data.CLICOD;
                        idProvedor.value = data.CLISUCURSAL;
                        idFactura.value = codigo.substring(0, 11);
                    }
                })
                .catch(error => console.error('Error al procesar el código:', error));
        } else {
            $('body').find('.alert-danger').remove(); // Elimina cualquier alerta previa
$('body').prepend(
    '<div class="alert alert-danger  text-center" role="alert"><strong>Error: </strong> El código debe tener más de 11 caracteres.</div>'
);
            //alert('El código debe tener más de 11 caracteres.');
        }
    });
</script>
@endsection