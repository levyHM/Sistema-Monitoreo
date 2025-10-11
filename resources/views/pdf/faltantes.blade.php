<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <title>Recibo de Faltante/Sobrante</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            font-size: 12px; /* letra un poco más pequeña */
            margin: 10px 30px 30px 30px;
            color: #2c3e50;
            position: relative;
        }

        /* Marca de agua imagen */
        .marca-agua {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 400px;
            opacity: 0.07;
            z-index: -1;
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

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #00953A;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }

        .logo img {
            height: 100px;
        }

        .info {
            text-align: right;
            font-size: 14px;
        }

        h1 {
            text-align: center;
            font-size: 18px;
            margin: 15px 0;
            color: #00953A;
        }

        .section-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .checkbox {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 1px solid #7f8c8d;
            text-align: center;
            line-height: 16px;
            font-size: 12px;
            margin-right: 3px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            page-break-inside: auto;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        th,
        td {
            border: 1px solid #bdc3c7;
            padding: 5px;
            text-align: left;
        }

        th {
            background-color: #ecf0f1;
        }

        /* Evidencias adaptables */
        .evidencias-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 10px;
            page-break-inside: auto;
        }

        .evidencias-table td {
            border: none;
            padding: 0;
            text-align: center;
            vertical-align: top;
        }

        .evidencias-table img {
            width: 100%;
            max-width: 180px;
            max-height: 180px;
            object-fit: cover;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        /* Firmas */
        .signatures {
            margin-top: 40px;
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
        }

        .signatures td {
            text-align: center;
            vertical-align: bottom;
            padding: 0 5px;
            border: none;
        }

        .signatures img {
            max-width: 60px;
            max-height: 60px;
            border-radius: 50%;
        }

        .signatures div {
            font-weight: bold;
            font-size: 11px;
            color: #2c3e50;
        }

        /* Saltos de página automáticos */
        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>

    @php
    function casillaCheckbox($valor) {
        return $valor === 'Si' ? '✓' : '';
    }
    $evidencias = $evidencias ?? [];
    $count = count($evidencias);
    @endphp

    {{-- Marca de agua imagen --}}
    <img src="{{ public_path('img/logo.png') }}" alt="Marca de agua" class="marca-agua" />

    {{-- Marca de agua texto cancelado --}}
    @if(isset($estatus) && strtolower($estatus) === 'cancelado')
    <div class="marca-agua-cancelado">CANCELADO</div>
    @endif

    <div class="header">
        <div class="logo">
            <img src="{{ public_path('img/logos/recibo_faltante-sobrante_cdmx.jpg') }}" alt="JIGAFRA Logo" />
        </div>
        <div class="info">
            <strong>Folio:</strong> <span style="color: red;">{{ $folio ?? '0878' }}</span><br />
            <strong>Fecha:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y') }}
        </div>
    </div>
    <h1>{{ $sucursal == 'F' ? 'CDMX' : $sucursal }}</h1>

    <div class="section">
        <div class="section-title">Datos del Proveedor</div>
        <p>
            <strong>Codigo de Proveedor:</strong> {{ $proveedor['nombre'] ?? 'PRVNOM' }}<br />
            <strong>Razon Social:</strong> {{ $proveedor['razon_social'] ?? 'PRVRAZ' }}
        </p>
    </div>

    <div class="section">
        <p>
            <strong>Faltante:</strong> <span class="checkbox">{{ $faltante === 'Sí' ? 'X' : '' }}</span>
            &nbsp;&nbsp;&nbsp;
            <strong>Sobrante:</strong> <span class="checkbox">{{ $sobrante === 'Sí' ? 'X' : '' }}</span>
        </p>
    </div>

    <div class="section">
        <div class="section-title">Detalle de Faltante/Sobrante</div>
        <table>
            <thead>
                <tr>
                    <th>Factura</th>
                    <th>Cantidad</th>
                    <th>No Parte</th>
                    <th>Codigo Proveedor</th>
                    <th>Descripción</th>
                    <th>Facturado</th>
                    <th>Fisico</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items ?? [] as $item)
                <tr>
                    <td style="text-align: center;">{{ $item['factura'] }}</td>
                    <td style="text-align: center;">{{ $item['cantidad'] }}</td>
                    <td style="text-align: center;">{{ $item['codigo'] }}</td>
                    <td style="text-align: center;">{{ $item['codigo_proveedor'] }}</td>
                    <td>{{ $item['descripcion'] }}</td>
                    <td style="text-align: center;">{{ $item['facturado'] }}</td>
                    <td style="text-align: center;">{{ $item['fisico'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if ($count > 0)
    <div class="section">
        <table class="evidencias-table">
            <tbody>
                <tr>
                    @foreach ($evidencias as $evidencia)
                    <td>
                        <img src="{{ public_path('storage/' . $evidencia->url) }}" />
                    </td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
    @endif

    @php
    $areas = [
        1 => 'Recibo',
        2 => 'Soluciones',
        3 => 'Almacen',
        4 => 'Compras',
        5 => 'Proveedor'
    ];
    @endphp

    <div class="section">
        <table class="signatures">
            <tr>
                @foreach ($areas as $idArea => $nombreArea)
                @php
                $firma = collect($firmas)->firstWhere('id', $idArea);
                @endphp
                <td>
                    @if(isset($firma['signature']) && trim($firma['signature']) !== '')
                    <img src="{{ public_path('storage/' . $firma['signature']) }}" alt="Firma">
                    @else
                    <div>Pendiente Por Firmar</div>
                    @endif
                    <div>{{ $firma['name'] ?? 'Pendiente' }}</div>
                    <div>{{ $nombreArea }}</div>
                </td>
                @endforeach
            </tr>
        </table>
    </div>

</body>

</html>