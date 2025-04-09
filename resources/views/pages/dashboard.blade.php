@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Dashboard'])
<div class="container-fluid py-4">
    <div class="row">
        <div class="row mt-4">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header text-center pt-4 pb-3">
                            <span class="badge rounded-pill bg-light text-dark">Factura CDMX</span>
                        </div>
                        <div class="card-body text-lg-left text-center pt-0">
                            <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
                                <a href="{{ url('factura-cdmx') }}" class="d-block">
                                    <img src="img/factura.png" class="img-fluid border-radius-lg"
                                        style="width: 100px; height: 100px;">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Factura Oaxaca --}}
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header text-center pt-4 pb-3">
                            <span class="badge rounded-pill bg-light text-dark">Factura Oaxaca</span>
                        </div>
                        <div class="card-body text-lg-left text-center pt-0">
                            <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
                                <a href="{{ url('factura-oaxaca') }}" class="d-block">
                                    <img src="img/factura.png" class="img-fluid border-radius-lg"
                                        style="width: 100px; height: 100px;">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Factura Xalapa --}}
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header text-center pt-4 pb-3">
                            <span class="badge rounded-pill bg-light text-dark">Factura Xalapa</span>
                        </div>
                        <div class="card-body text-lg-left text-center pt-0">
                            <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
                                <a href="{{ url('factura-xalapa') }}" class="d-block">
                                    <img src="img/factura.png" class="img-fluid border-radius-lg"
                                        style="width: 100px; height: 100px;">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- ROW de Pedidos --}}
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card">
                        {{-- Pedido CMDX --}}
                        <div class="card-header text-center pt-4 pb-3">
                            <span class="badge rounded-pill bg-light text-dark">Pedido CDMX</span>
                        </div>
                        <div class="card-body text-lg-left text-center pt-0">
                            <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
                                <a href="{{ url('pedidos-cdmx') }}" class="d-block">
                                    <img src="img/pedido.png" class="img-fluid border-radius-lg"
                                        style="width: 100px; height: 100px;">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Pedido Oaxaca --}}
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header text-center pt-4 pb-3">
                            <span class="badge rounded-pill bg-light text-dark">Pedido Oaxaca</span>
                        </div>
                        <div class="card-body text-lg-left text-center pt-0">
                            <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
                                <a href="{{ url('pedidos-oaxaca') }}" class="d-block">
                                    <img src="img/pedido.png" class="img-fluid border-radius-lg"
                                        style="width: 100px; height: 100px;">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Pedido Xalapa --}}
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header text-center pt-4 pb-3">
                            <span class="badge rounded-pill bg-light text-dark">Pedido Xalapa</span>
                        </div>
                        <div class="card-body text-lg-left text-center pt-0">
                            <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
                                <a href="{{ url('pedidos-xalapa') }}" class="d-block">
                                    <img src="img/pedido.png" class="img-fluid border-radius-lg"
                                        style="width: 100px; height: 100px;">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- ROW Embarques --}}
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card">
                        {{-- Pedido CMDX --}}
                        <div class="card-header text-center pt-4 pb-3">
                            <span class="badge rounded-pill bg-light text-dark">Embarque CDMX</span>
                        </div>
                        <div class="card-body text-lg-left text-center pt-0">
                            <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
                                <a href="javascript:;" class="d-block">
                                    <img src="img/embarque.png" class="img-fluid border-radius-lg"
                                        style="width: 100px; height: 100px;">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Pedido Oaxaca --}}
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header text-center pt-4 pb-3">
                            <span class="badge rounded-pill bg-light text-dark">Embarque Oaxaca</span>
                        </div>
                        <div class="card-body text-lg-left text-center pt-0">
                            <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
                                <a href="javascript:;" class="d-block">
                                    <img src="img/embarque.png" class="img-fluid border-radius-lg"
                                        style="width: 100px; height: 100px;">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Pedido Xalapa --}}
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header text-center pt-4 pb-3">
                            <span class="badge rounded-pill bg-light text-dark">Embarque Xalapa</span>
                        </div>
                        <div class="card-body text-lg-left text-center pt-0">
                            <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
                                <a href="javascript:;" class="d-block">
                                    <img src="img/embarque.png" class="img-fluid border-radius-lg"
                                        style="width: 100px; height: 100px;">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
    @endsection

    @push('js')
    <script src="./assets/js/plugins/chartjs.min.js"></script>

    @endpush