<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <title>Reporte de Faltante</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            font-size: 12px;
            margin: 10px 30px 30px 30px;
            color: #2c3e50;
            position: relative;
        }

        .marca-agua {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 400px;
            opacity: 0.07;
            z-index: -1;
        }

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

        .indicadores-horizontal {
            margin-top: 15px;
            font-size: 13px;
            font-weight: 500;
            color: #2c3e50;
        }

        .indicadores-horizontal span {
            display: inline-block;
            margin-right: 30px;
        }

        .checkbox-square {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 1px solid #7f8c8d;
            text-align: center;
            line-height: 16px;
            font-size: 12px;
            font-weight: bold;
            margin-right: 5px;
        }

        .firmas-table {
            text-align: center;
            margin-top: 40px;
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
    </style>
</head>

<body>

    {{-- Marca de agua --}}
    <img src="{{ public_path('img/logo.png') }}" alt="Marca de agua" class="marca-agua" />

    @if(isset($reporte->estatus) && strtolower($reporte->estatus) === '3')
    <div class="marca-agua-cancelado">CANCELADO</div>
    @endif

    {{-- Encabezado --}}
    <div class="header">
        <div class="logo">
            <img src="{{ public_path('img/logos/faltante_cdmx.jpeg') }}" alt="JIGAFRA Logo" />
        </div>
        <div class="info">
            <strong>Folio:</strong> <span style="color: red;">{{ str_pad($reporte->idreporte_faltante, 5, '0',
                STR_PAD_LEFT) }}</span><br />
            <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($reporte->fecha)->format('d/m/Y') }}
        </div>
    </div>

    {{-- Datos del cliente --}}
    <div class="section">
        <div class="section-title">Datos del Cliente</div>
        <p>
            <strong>Cliente:</strong> {{ $reporte->catalogoFaltante->codigo ?? '' }}<br />
            <strong>Razón Social:</strong> {{ $reporte->catalogoFaltante->codigo_nombre ?? '' }}<br />
            <strong>Zona:</strong> {{ $reporte->catalogoFaltante->zona ?? '' }}
        </p>
    </div>

    {{-- Detalle de faltante --}}
    <div class="section">
        <div class="section-title">Detalle de Faltante</div>
        <table>
            <thead>
                <tr>
                    <th>Cantidad</th>
                    <th>No. Factura</th>
                    <th>No. Parte</th>
                    <th>Descripción</th>
                    <th>P. Unitario</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach ($reporte->reportesFalta as $item)
                @php $precio = $item->catalogoProducto->aiprecio ?? 0; $total += $precio; @endphp
                <tr>
                    <td>{{ $item->cantidad }}</td>
                    <td>{{ $item->numero_factura }}</td>
                    <td>{{ $item->catalogoProducto->icod ?? '' }}</td>
                    <td>{{ $item->catalogoProducto->idescr ?? '' }}</td>
                    <td>${{ number_format($precio, 2) }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="4" style="text-align: right;"><strong>Total:</strong></td>
                    <td><strong>${{ number_format($total, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Motivo y solución --}}
    <div class="section">
        <p><strong>Motivo del Faltante:</strong> {{ $reporte->motivoFaltante->descripcion ?? 'N/A' }}</p>
        <p><strong>Solución:</strong> {{ $reporte->solucion ?? 'N/A' }}</p>
    </div>

    {{-- Indicadores --}}
    <div class="section">
        <div class="section-title">Indicadores</div>
        <div class="indicadores-horizontal">
            <span>
                <div class="checkbox-square">{{ $reporte->catalogo_reporte_faltante_tipo_id == 1 ? 'X' : '' }}</div>
                Procede
            </span>
            <span>
                <div class="checkbox-square">{{ $reporte->catalogo_reporte_faltante_tipo_id == 2 ? 'X' : '' }}</div>
                Cambio Físico
            </span>
            <span>
                <div class="checkbox-square">{{ $reporte->catalogo_reporte_faltante_tipo_id == 3 ? 'X' : '' }}</div> NC
                por Servicio
            </span>
        </div>
    </div>


    {{-- Firma centrada --}}
    {{-- Firma centrada --}}
    <div class="firmas-table">
        @if($reporte->userAutorizo && $reporte->userAutorizo->signature && file_exists(public_path('storage/' .
        $reporte->userAutorizo->signature)))
        <img src="{{ public_path('storage/' . $reporte->userAutorizo->signature) }}" class="firma-img"
            alt="Firma {{ $reporte->userAutorizo->firstname }}"
            style="max-width: 120px; max-height: 60px; display:block; margin:0 auto 5px auto;">
        @else
        {{-- Línea para firma si no hay imagen --}}
        <div style="width:120px; border-bottom:1px solid #000; margin:0 auto 5px auto;"></div>
        @endif

        {{-- Nombre del usuario --}}
        <p style="margin:0; font-weight:bold; text-align:center;">
            {{ $reporte->userAutorizo
            ? $reporte->userAutorizo->firstname . ' ' . $reporte->userAutorizo->lastname
            : 'JEFE DE ALMACÉN' }}
        </p>

        {{-- Etiqueta Firma --}}
        <p style="margin:0; text-align:center;">Jefe de Almacén</p>
    </div>

</body>

</html>