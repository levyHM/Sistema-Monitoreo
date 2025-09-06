<?php

namespace App\Http\Controllers;

use App\Models\Recibo;
use Barryvdh\DomPDF\Facade\Pdf;;

use Illuminate\Support\Facades\Log;

class PDFController extends Controller
{
    public function generarRecibo($id)
    {
        // Cargar recibo con proveedor, conceptos y evidencias relacionados
        $recibo = Recibo::with(['proveedor', 'conceptos.producto', 'evidencias', 'firma.usuario', 'conceptosEstado'])->findOrFail($id);

        // Preparar datos para la vista
        $datos = [
            'folio' => $recibo->idrecibos,
            'sucursal' => $recibo->sucursal ?? 'N/A',
            'proveedor' => [
                'razon_social' => $recibo->proveedor->prvnom ?? '',
                'nombre' => $recibo->proveedor->prvcod ?? '',
            ],
            'concepto' => ($recibo->conceptosEstado->first()?->devolucion ?? false) ? 'Sí' : 'No',
            'factura' => $recibo->factura ?? 'N/A',
            'motivo' => $recibo->motivo ?? 'N/A',
            'items' => $recibo->conceptos->map(function ($concepto) {
                return [
                    'cantidad' => $concepto->cantidad,
                    'codigo' => $concepto->producto->icod ?? '',
                    'codigo_proveedor' => $concepto->producto->icodprv ?? '',
                    'descripcion' => $concepto->producto->idescrip ?? '',
                    'observaciones' => $concepto->observaciones,
                    'factura' => $concepto->numero_factura,
                    'facturado' => $concepto->facturado ?? '',
                    'fisico' => $concepto->fisico ?? '',
                ];
            })->toArray(),
            'evidencias' => $recibo->evidencias,  // Pasar evidencias a la vista
            'firmas' => $recibo->firmas->map(function ($firma) {
                return [
                    'id' => $firma->catalogo_firma_idcatalogo_firma,
                    'signature' => $firma->usuario->signature ?? '',
                    'name' => trim(($firma->usuario->firstname ?? '') . ' ' . ($firma->usuario->lastname ?? '')),
                ];
            })->toArray(),
            'estatus' => $recibo->estatus ?? 'cancelado',  // <-- Aquí agregas el estatus (ajusta según el nombre correcto)

        ];
        /**
         * Genera un registro en el log informando que se está generando un PDF para el recibo especificado por el ID.
         * Incluye los datos relevantes en el log para facilitar el monitoreo y la trazabilidad del proceso de generación de PDFs.
         *
         * @param int $id Identificador del recibo para el cual se genera el PDF.
         * @param array $datos Datos adicionales relacionados con el recibo y el proceso de generación del PDF.
         */
        Log::info('Generando PDF para recibo ID: ' . $id, $datos);

        $pdf = Pdf::loadView('pdf.recibo', $datos);

        return $pdf->download('recibo-' . $id . '.pdf');
    }

    public function generarFaltante($id)
    {
        // Cargar recibo con proveedor, conceptos y evidencias relacionados
        $recibo = Recibo::with(['proveedor', 'conceptos.producto', 'evidencias', 'firma', 'conceptosEstado'])->findOrFail($id);

        // Preparar datos para la vista
        $datos = [
            'folio' => $recibo->idrecibos,
            'sucursal' => $recibo->sucursal ?? 'N/A',
            'proveedor' => [
                'razon_social' => $recibo->proveedor->prvnom ?? '',
                'nombre' => $recibo->proveedor->prvcod ?? '',
            ],
            'faltante' => ($recibo->conceptosEstado->first()?->faltante ?? false) ? 'Sí' : 'No',
            'sobrante' => ($recibo->conceptosEstado->first()?->sobrante ?? false) ? 'Sí' : 'No',
            'factura' => $recibo->factura ?? 'N/A',
            'motivo' => $recibo->motivo ?? 'N/A',
            'items' => $recibo->conceptos->map(function ($concepto) {
                return [
                    'cantidad' => $concepto->cantidad,
                    'codigo' => $concepto->producto->icod ?? '',
                    'codigo_proveedor' => $concepto->producto->icodprv ?? '',
                    'descripcion' => $concepto->producto->idescrip ?? '',
                    'observaciones' => $concepto->observaciones,
                    'factura' => $concepto->numero_factura,
                    'facturado' => $concepto->facturado ?? '',
                    'fisico' => $concepto->fisico ?? '',
                ];
            })->toArray(),
            'evidencias' => $recibo->evidencias,  // Pasar evidencias a la vista
            'firmas' => $recibo->firmas->map(function ($firma) {
                return [
                    'id' => $firma->catalogo_firma_idcatalogo_firma,
                    'signature' => $firma->usuario->signature ?? '',
                    'name' => trim(($firma->usuario->firstname ?? '') . ' ' . ($firma->usuario->lastname ?? '')),
                ];
            })->toArray(),
            'estatus' => $recibo->estatus ?? 'cancelado',  // <-- Aquí agregas el estatus (ajusta según el nombre correcto)

        ];
        /**
         * Genera un registro en el log informando que se está generando un PDF para el recibo especificado por el ID.
         * Incluye los datos relevantes en el log para facilitar el monitoreo y la trazabilidad del proceso de generación de PDFs.
         *
         * @param int $id Identificador del recibo para el cual se genera el PDF.
         * @param array $datos Datos adicionales relacionados con el recibo y el proceso de generación del PDF.
         */
        Log::info('Generando PDF para recibo ID: ' . $id, $datos);

        $pdf = Pdf::loadView('pdf.faltantes', $datos);

        return $pdf->download('faltante-' . $id . '.pdf');
    }
}
