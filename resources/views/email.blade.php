<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Notificación de Reporte</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="margin:0; padding:0; background-color:#f4f6f5; font-family: Arial, Helvetica, sans-serif;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6f5; padding:40px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 6px 16px rgba(0,0,0,0.06);">

                    <!-- Encabezado sin logo -->
                    <tr>
                        <td style="background-color:#285C4D; padding:24px 28px; text-align:center;">
                            <h2 style="margin:0; font-size:22px; color:#ffffff;">🔔 Reporte generado</h2>
                            <p style="margin:6px 0 0 0; font-size:13px; color:#d7ebe3;">
                                Sistema de monitoreo • {{ config('app.name') }}
                            </p>
                        </td>
                    </tr>

                    <!-- Título y resumen -->
                    <tr>
                        <td style="padding:26px 28px;">
                            <p style="margin:0 0 8px 0; font-size:15px; color:#4a4a4a;">
                                Hola <strong>{{ $solucion->cliente->nombre ?? 'Cliente' }}</strong>, se ha generado un nuevo reporte en el sistema.
                            </p>

                            <!-- Metadatos del reporte -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top:16px; font-size:14px; color:#555;">
                                <tr>
                                    <td style="padding:6px 0; width:140px;"><strong>Razón social:</strong></td>
                                    <td style="padding:6px 0;">{{ $solucion->razon_social }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 0;"><strong>Folio:</strong></td>
                                    <td style="padding:6px 0;">
                                        {{ str_pad($solucion->idreporte_soluciones_clientes, 4, '0', STR_PAD_LEFT) }}{{ $solucion->cliente->clipar1 ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 0;"><strong>Estatus:</strong></td>
                                    <td style="padding:6px 0;">
                                        @php
                                            $estatus = strtolower($solucion->estatus ?? '');
                                            $badgeBg = match($estatus) {
                                                'aprobado' => '#e6f5ea',
                                                'rechazado', 'no aprobado' => '#fdecec',
                                                'garantía', 'garantia' => '#fff7e6',
                                                default => '#eef2f1'
                                            };
                                            $badgeColor = match($estatus) {
                                                'aprobado' => '#2e7d32',
                                                'rechazado', 'no aprobado' => '#c62828',
                                                'garantía', 'garantia' => '#8a6d1e',
                                                default => '#285C4D'
                                            };
                                        @endphp
                                        <span style="display:inline-block; padding:4px 10px; border-radius:999px; background:{{ $badgeBg }}; color:{{ $badgeColor }}; font-weight:bold;">
                                            {{ ucfirst($solucion->estatus) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 0;"><strong>Fecha:</strong></td>
                                    <td style="padding:6px 0;">{{ $solucion->fecha }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Tabla de productos -->
                    <tr>
                        <td style="padding:0 28px 20px 28px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse; font-size:13.5px;">
                                <thead>
                                    <tr style="background-color:#e9f3ef; color:#285C4D;">
                                        <th align="left" style="padding:10px;">Factura</th>
                                        <th align="left" style="padding:10px;">Código</th>
                                        <th align="left" style="padding:10px;">Descripción</th>
                                        <th align="center" style="padding:10px; width:90px;">Cantidad</th>
                                        <th align="right" style="padding:10px; width:120px;">Total</th>
                                    </tr>
                                </thead>
                                <tbody style="border-top:1px solid #e6e6e6;">
                                    @foreach ($productos as $p)
                                    <tr style="border-bottom:1px solid #eeeeee;">
                                        <td style="padding:10px;">{{ $p->factura }}</td>
                                        <td style="padding:10px;">{{ $p->icod }}</td>
                                        <td style="padding:10px;">{{ $p->descripcion }}</td>
                                        <td align="center" style="padding:10px;">{{ $p->cantidad }}</td>
                                        <td align="right" style="padding:10px;">${{ number_format($p->total, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <!-- Pie de página -->
                    <tr>
                        <td style="background-color:#f8f9f9; padding:18px 28px; text-align:center; font-size:12.5px; color:#7a7a7a;">
                            Este correo fue generado automáticamente.<br>
                            JIGAFRA S.A. DE C.V. &copy; {{ date('Y') }}
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
