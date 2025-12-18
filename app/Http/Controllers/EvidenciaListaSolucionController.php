<?php

namespace App\Http\Controllers;

use App\Models\EvidenciaListaSolucion;
use App\Models\ListaSolucionesCliente;
use App\Models\ReporteSolucionesCliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EvidenciaListaSolucionController extends Controller
{
    /**
     * Guardar evidencias asociadas a una solución específica
     */
    public function store(Request $request, $solucionId)
    {
        Log::info('**********************************************************************');
        Log::info('Iniciando proceso de guardado de evidencias', [
            'solucion_id' => $solucionId,
            'request_data' => $request->all()
        ]);

        try {
            $solucion = ListaSolucionesCliente::findOrFail($solucionId);
            $reporte = ReporteSolucionesCliente::with('soluciones')->findOrFail($solucion->reporte_soluciones_clientes_idreporte_soluciones_clientes);
            Log::info('Solución encontrada', [
                'solucion_id' => $solucion->idlista_soluciones_clientes,
                'idreporte_soluciones_clientes' => $solucion->reporte_soluciones_clientes_idreporte_soluciones_clientes
            ]);

            // Validar archivos
            $request->validate([
                'evidencias.*' => 'required|mimetypes:image/jpeg,image/png,image/webp,image/gif,application/pdf|max:5120',
                'facturas.0.fecha_operador' => 'required|date|after_or_equal:today',
                'facturas.0.ruta' => 'required|string|max:255',
            ]);
            Log::info('Validación de archivos exitosa');

            // Actualizar campos de la solución
            $solucion->update([
                'fecha_operador' => $request->input('facturas.0.fecha_operador'),
                'ruta'           => $request->input('facturas.0.ruta'),
            ]);
            Log::info('Solución actualizada', [
                'fecha_operador' => $request->input('facturas.0.fecha_operador'),
                'ruta'           => $request->input('facturas.0.ruta')
            ]);

            // Contar evidencias actuales
            $existentes = $solucion->evidencias()->count();
            $nuevas = $request->file('evidencias') ?? [];

            Log::info('Conteo de evidencias', [
                'existentes' => $existentes,
                'nuevas'     => count($nuevas)
            ]);

            if ($existentes + count($nuevas) > 3) {
                Log::warning('Límite de evidencias excedido', [
                    'existentes' => $existentes,
                    'nuevas'     => count($nuevas)
                ]);
                return back()->with('error', 'Solo se permiten 3 evidencias en total.');
            }

            // Guardar una por una
            foreach ($nuevas as $index => $archivo) {
                Log::info('Procesando archivo', [
                    'index'            => $index,
                    'nombre_original'  => $archivo->getClientOriginalName(),
                    'tamaño'           => $archivo->getSize()
                ]);

                $ruta = $archivo->store('evidencias_soluciones/' . $solucion->idlista_soluciones_clientes, 'public');

                Log::info('Archivo guardado en storage', [
                    'index'            => $index,
                    'ruta'             => $ruta,
                    'nombre_original'  => $archivo->getClientOriginalName()
                ]);

                $evidencia = $solucion->evidencias()->create([
                    'lista_soluciones_clientes_id' => $solucion->idlista_soluciones_clientes,
                    'archivo'                      => $ruta,
                ]);

                Log::info('Evidencia creada en base de datos', [
                    'evidencia_id' => $evidencia->id,
                    'archivo'      => $ruta
                ]);
            }

            Log::info('Evidencias agregadas exitosamente', [
                'total_agregadas'   => count($nuevas),
                'total_evidencias'  => $solucion->evidencias()->count()
            ]);
            
            $this->validacionEstatus($solucion->reporte_soluciones_clientes_idreporte_soluciones_clientes);

            Log::info('**********************************************************************');

            return back()->with('success', 'Evidencias agregadas correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al guardar evidencias', [
                'solucion_id' => $solucionId,
                'error'       => $e->getMessage(),
                'trace'       => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Ocurrió un error al guardar las evidencias. Intenta nuevamente.');
        }
    }


    /**
     * Actualizar metadatos y evidencias de una solución existente
     */
    public function update(Request $request, $solucionId)
    {
        Log::info('**********************************************************************');
        Log::info('🔄 Iniciando actualización de solución', [
            'solucion_id' => $solucionId,
            'request_data' => $request->all()
        ]);

        $solucion = ListaSolucionesCliente::findOrFail($solucionId);
        Log::info('✅ Solución encontrada', ['solucion_id' => $solucion->idlista_soluciones_clientes]);

        // Validar datos
        $request->validate([
            'facturas.0.fecha_operador' => 'required|date|after_or_equal:today',
            'facturas.0.ruta' => 'required|string|max:255',
            'evidencias.*' => 'nullable|mimetypes:image/jpeg,image/png,image/webp,image/gif,application/pdf|max:5120',
        ]);

        // Actualizar campos
        $solucion->update([
            'fecha_operador' => $request->input('facturas.0.fecha_operador'),
            'ruta' => $request->input('facturas.0.ruta'),
        ]);

        Log::info('📝 Solución actualizada', [
            'fecha_operador' => $solucion->fecha_operador,
            'ruta' => $solucion->ruta
        ]);

        // Si hay nuevas evidencias, procesarlas
        $nuevas = $request->file('evidencias') ?? [];

        if (count($nuevas) > 0) {
            $existentes = $solucion->evidencias()->count();

            if ($existentes + count($nuevas) > 3) {
                Log::warning('⚠️ Límite de evidencias excedido en actualización', [
                    'existentes' => $existentes,
                    'nuevas' => count($nuevas)
                ]);
                return back()->with('error', 'Solo se permiten 3 evidencias en total.');
            }

            foreach ($nuevas as $index => $archivo) {
                Log::info('📤 Procesando nueva evidencia', [
                    'index' => $index,
                    'nombre_original' => $archivo->getClientOriginalName(),
                    'tamaño' => $archivo->getSize()
                ]);

                $ruta = $archivo->store('evidencias_soluciones/' . $solucion->idlista_soluciones_clientes, 'public');

                $evidencia = $solucion->evidencias()->create([
                    'lista_soluciones_clientes_id' => $solucion->idlista_soluciones_clientes,
                    'archivo' => $ruta,
                ]);

                Log::info('✅ Evidencia guardada', [
                    'evidencia_id' => $evidencia->id,
                    'archivo' => $ruta
                ]);
            }
        }

        Log::info('✅ Actualización completa');
        Log::info('**********************************************************************');
        return back()->with('success', 'Solución actualizada correctamente.');
    }

    /**
     * Eliminar una evidencia específica
     */
    public function destroy($solucionId, $evidenciaId)
    {
        Log::info('**********************************************************************');
        Log::info('🗑️ Iniciando eliminación de evidencia', [
            'solucion_id' => $solucionId,
            'evidencia_id' => $evidenciaId
        ]);

        $solucion = ListaSolucionesCliente::findOrFail($solucionId);
        Log::info('✅ Solución encontrada', ['solucion_id' => $solucion->idlista_soluciones_clientes]);

        $evidencia = $solucion->evidencias()->findOrFail($evidenciaId);
        Log::info('✅ Evidencia encontrada', [
            'evidencia_id' => $evidencia->id,
            'archivo' => $evidencia->archivo
        ]);

        // Eliminar archivo del storage
        $archivoEliminado = Storage::disk('public')->delete($evidencia->archivo);
        Log::info('📁 Archivo eliminado del storage', [
            'archivo' => $evidencia->archivo,
            'resultado' => $archivoEliminado ? 'exitoso' : 'fallido'
        ]);

        // Eliminar registro de base de datos
        $evidencia->delete();
        Log::info('✅ Registro de evidencia eliminado de la base de datos', [
            'evidencia_id' => $evidenciaId
        ]);

        Log::info('✅ Evidencia eliminada correctamente', [
            'total_evidencias_restantes' => $solucion->evidencias()->count()
        ]);

        Log::info('**********************************************************************');
        return back()->with('success', 'Evidencia eliminada correctamente.');
    }

    public function validacionEstatus($idreporte_soluciones_clientes)
    {
        Log::info('Iniciando validación de estatus', [
            'idreporte_soluciones_clientes' => $idreporte_soluciones_clientes
        ]);

        $reporte = ReporteSolucionesCliente::with('soluciones')->findOrFail($idreporte_soluciones_clientes);
        
        if ($reporte->catalogo_tipo_id == 2) {
            $reporte->update([
                'estatus' => 4,
            ]);
            
            Log::info('Estatus actualizado', [
                'reporte_id' => $reporte->id,
                'estatus_nuevo' => 4
            ]);
        } else {
            Log::info('No se requiere actualización de estatus', [
                'reporte_id' => $reporte->id,
                'catalogo_tipo_id' => $reporte->catalogo_tipo_id
            ]);
        }
    }
}
