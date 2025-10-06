<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte Soluciones Cliente</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 20px 30px;
            color: #000;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #00953A;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }

        .logo img {
            height: 60px;
        }

        .empresa {
            text-align: center;
            flex-grow: 1;
            font-size: 16px;
            font-weight: bold;
            color: #00953A;
        }

        .empresa small {
            display: block;
            font-weight: normal;
            font-size: 12px;
            color: #555;
        }

        .folio-fecha {
            text-align: right;
            font-size: 12px;
        }

        .folio {
            color: #fc1e05;
            font-weight: bold;
            font-size: 14px;
        }

        /* Datos cliente */
        .datos-cliente {
            margin-top: 10px;
            font-size: 12px;
        }

        .datos-cliente td {
            padding: 5px 8px;
            border: none;
        }

        /* Indicadores */
        .indicadores {
            margin-top: 8px;
            font-size: 12px;
        }

        .indicadores span {
            margin-right: 25px;
            font-weight: bold;
        }

        /* Tabla facturas */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
            font-size: 11px;
        }

        th,
        td {
            border: none;
            /* Sin bordes */
            padding: 6px;
            text-align: center;
        }

        tbody tr:nth-child(even) {
            background-color: #f8f9f9;
        }

        th {
            background-color: #e0f2e9;
            font-weight: bold;
        }

        /* Totales */
        .totales {
            margin-top: 10px;
            width: 35%;
            float: right;
            font-size: 12px;
        }

        .totales td {
            padding: 6px;
            border: none;
            /* quitar borde */
            text-align: right;
        }

        .totales tr:last-child td {
            font-weight: bold;
            background-color: #e0f2e9;
        }

        /* Firmas horizontales estilo tabla */
        .firmas-table {
            width: 100%;
            margin-top: 50px;
            border-collapse: collapse;
        }

        .firmas-table td {
            text-align: center;
            vertical-align: top;
            width: 25%;
            padding: 10px 5px;
            border: none;
            /* quitar borde */
        }

        .firma-img {
            max-height: 70px;
            display: block;
            margin: 0 auto 6px auto;
        }

        .firma-linea {
            border-top: 1px solid #555;
            width: 80%;
            margin: 0 auto 6px auto;
        }

        .firma-nombre {
            font-weight: bold;
            margin-bottom: 2px;
        }

        .firma-label {
            font-size: 11px;
            color: #555;
        }

        .pendiente {
            font-size: 10px;
            color: #999;
            margin-bottom: 4px;
        }

        /* Marca de agua texto "CANCELADO" */
        .marca-agua-cancelado {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            font-weight: bold;
            color: red;
            opacity: 0.15;
            z-index: 9999;
            pointer-events: none;
            user-select: none;
            white-space: nowrap;
            text-transform: uppercase;
            font-family: Arial, sans-serif;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <div class="header">
        <div class="logo">
            <img src="{{ public_path('img/logos/soluciones_cliente.png') }}" alt="Logo">
        </div>
        <div class="empresa">
            JIGAFRA S A DE C V
            <small>Dpto. de Soluciones a Clientes - CDMX</small>
        </div>
        <div class="folio-fecha">
            <strong>Folio:</strong> <span class="folio">{{ str_pad($folio, 5, '0', STR_PAD_LEFT) }}</span><br>
            <strong>Fecha:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y') }}<br>
            <strong>Cobrador:</strong> {{ $cliente['colaborador'] }}
        </div>
    </div>
    {{-- Marca de agua texto cancelado --}}
    @if(isset($estatus) && strtolower($estatus) === 'cancelado')
    <div class="marca-agua-cancelado">CANCELADO</div>
    @endif
    <!-- Datos del cliente -->
    <table class="datos-cliente">
        <tr>
            <td><strong>Clave Cliente:</strong> {{ $cliente['clicod'] }}</td>
            <td><strong>Razón Social:</strong> {{ $cliente['razon_social'] }}</td>
        </tr>
    </table>

    <!-- Indicadores -->
    <div class="indicadores">
        <span>Devolución: {{ $devolucion }}</span>
        <span>Garantía: {{ $garantia }}</span>
    </div>

    <!-- Tabla de facturas -->
    <table>
        <thead>
            <tr>
                <th>No. Factura</th>
                <th>Cantidad</th>
                <th>Código</th>
                <th>Descripción</th>
                <th>P. Unitario</th>
                <th>Total</th>
                <th>Observaciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($facturas as $item)
            <tr>
                <td>{{ $item['factura'] }}</td>
                <td>{{ $item['cantidad'] }}</td>
                <td>{{ $item['icod'] }}</td>
                <td>{{ $item['descripcion'] }}</td>
                <td>${{ number_format($item['p_unitario'], 2) }}</td>
                <td>${{ number_format($item['total'], 2) }}</td>
                <td>{{ $item['observaciones'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totales -->
    <table class="totales">
        <tr>
            <td>Subtotal:</td>
            <td>${{ number_format($totales['subtotal'], 2) }}</td>
        </tr>
        <tr>
            <td>Descuento:</td>
            <td>${{ number_format($totales['descuento'], 2) }}</td>
        </tr>
        <tr>
            <td>IVA:</td>
            <td>${{ number_format($totales['iva'], 2) }}</td>
        </tr>
        <tr>
            <td>Total:</td>
            <td>${{ number_format($totales['total_completo'], 2) }}</td>
        </tr>
    </table>

    <div style="clear: both;"></div>

    <!-- Firmas horizontales -->
    <!-- Primer bloque: Soluciones y Crédito -->
    @php
    $firmas_superiores = [
    ['imagen'=>$reporte->firmanteSoluciones?->signature, 'nombre'=>$reporte->firmanteSoluciones?->firstname . ' ' .
    $reporte->firmanteSoluciones?->lastname, 'label'=>'Soluciones'],
    ['imagen'=>$reporte->firmanteCredito?->signature, 'nombre'=>$reporte->firmanteCredito?->firstname . ' ' .
    $reporte->firmanteCredito?->lastname, 'label'=>'Crédito y Cobranza'],
    ];
    @endphp

    <table class="firmas-table">
        <tr>
            @foreach($firmas_superiores as $firma)
            <td>
                @if($firma['imagen'] && file_exists(public_path('storage/' . $firma['imagen'])))
                <img src="{{ public_path('storage/' . $firma['imagen']) }}" class="firma-img"
                    alt="Firma {{ $firma['label'] }}">
                @else
                <div class="firma-linea"></div>
                <div class="pendiente">Pendiente por firmar</div>
                @endif
                <div class="firma-nombre">{{ $firma['nombre'] ?? 'Pendiente' }}</div>
                <div class="firma-label">{{ $firma['label'] }}</div>
            </td>
            @endforeach
        </tr>
    </table>

    <!-- Segundo bloque: Cliente y Cobrador -->
    @php
    $firmas_inferiores = [
    ['imagen'=>$reporte->firmanteCliente?->signature, 'nombre'=>$reporte->firmanteCliente?->firstname . ' ' .
    $reporte->firmanteCliente?->lastname, 'label'=>'Cliente'],
    ['imagen'=>$reporte->cliente?->signature, 'nombre'=>$reporte->cliente->clinom ?? '',
    'zona'=>$reporte->cliente->clipar1 ?? '', 'label'=>'Cobrador'],
    ];
    @endphp

    <table class="firmas-table">
        <tr>
            @foreach($firmas_inferiores as $firma)
            <td>
                <strong>{{ $firma['zona'] ?? '' }}</strong><br>
                <div class="firma-linea"></div>
                <div class="firma-nombre">{{ $firma['nombre'] ?? 'Pendiente' }}</div>
                <div class="firma-label">{{ $firma['label'] }}</div>
            </td>
            @endforeach
        </tr>
    </table>


</body>

</html>