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
                <div class="alert alert-success text-center">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                <div class="alert alert-danger text-center">{{ session('error') }}</div>
                @endif

                <div class="text-center mt-4">
                    <button id="updateButton" class="btn btn-info btn-md">Actualizar Clientes</button>
                </div>
        


     
            </div>
        </div>

    </div>
</div>

@include('layouts.footers.auth.footer')
@endsection