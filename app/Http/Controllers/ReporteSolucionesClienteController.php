<?php

namespace App\Http\Controllers;

use App\Models\CatalogoTipo;
use App\Models\Conductor;
use App\Models\ReporteSolucionesCliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\ListaSolucionesCliente;

class ReporteSolucionesClienteController extends Controller
{
    // Mostrar listado de reportes con filtros
    public function index(Request $request)
    {
        $query = ReporteSolucionesCliente::with(['cliente', 'soluciones', 'catalogoTipo'])
            ->orderBy('idreporte_soluciones_clientes', 'desc');

        // Filtros dinámicos
        if ($request->filled('fecha')) {
            $query->whereDate('fecha', $request->fecha);
            Log::info('Filtrando por fecha: ' . $request->fecha);
        }

        if ($request->filled('codigo')) {
            $query->whereHas('cliente', function ($q) use ($request) {
                $q->where('clicod', 'like', "%{$request->codigo}%");
            });
        }

        if ($request->filled('catalogo_tipo_id')) {
            $query->where('catalogo_tipo_id', $request->catalogo_tipo_id);
        }

        if ($request->filled('estatus')) {
            $query->where('estatus', $request->estatus);
        }
        // Obtener todos los tipos para el filtro
        $tipos = CatalogoTipo::all();

        $reportes = $query->paginate(100)->appends($request->query());

        return view('soluciones.index', compact('reportes', 'tipos'));
    }

    // Mostrar formulario de creación
    public function create(Request $request)
    {
        $operadores = Conductor::orderBy('operador')->get();
        $tipo = $request->tipo ?? 1; // Por defecto Garantía
        return view('soluciones.create', compact('tipo', 'operadores'));
    }

    // Guardar nuevo reporte
    public function store(Request $request)
    {
        Log::info('**********************************************************************');
        Log::info('Iniciando creación de nuevo reporte de soluciones');
        
        $request->validate([
            'catalogo_clientes_idcatalogo_clientes' => 'required|integer',
            'catalogo_tipo_id' => 'required|in:1,2',
            'observaciones' => 'nullable|string|max:255',
            'total' => 'required|numeric',
            'descuento' => 'required|numeric',
            'subtotal' => 'required|numeric',
            'iva' => 'required|numeric',
            'total_completo' => 'required|numeric',
            'estatus' => 'required',
            'facturas' => 'required|array|min:1',
            'facturas.*.catalogo_idcatalogo' => 'required|integer',
            'facturas.*.factura' => 'required|string|max:100',
            'facturas.*.cantidad' => 'required|integer|min:1',
            'facturas.*.total' => 'required|numeric|min:0',

        ]);
        
        Log::info('Validación completada exitosamente');
        Log::info('Datos del reporte:', $request->all());
        
        // Crear el reporte principal
        $reporte = ReporteSolucionesCliente::create([
            'fecha' => now(),
            'catalogo_clientes_idcatalogo_clientes' => $request->catalogo_clientes_idcatalogo_clientes,
            'cliente' => $request->cliente,
            'razon_social' => $request->razon_social,
            'colaborador' => $request->colaborador,
            'devolucion' => $request->has('devolucion') ? 1 : 0,
            'catalogo_tipo_id' => $request->catalogo_tipo_id, // ← Aquí se define si es Garantía (1) o Devolución (2)            
            'descuento' => $request->descuento,
            'total' => $request->total,
            'subtotal' => $request->subtotal,
            'iva' => $request->iva,
            'total_completo' => $request->total_completo,
            'estatus' => $request->estatus,
            /**
         * Estatus posibles para el campo 'estatus':
         * 0 => 'N/A'   // N/A
         * 1 => 'Aprobado'   // El elemento se encuentra en proceso de recolección.
         * 2 => 'No aprobado'       // El elemento está almacenado.
         * 3 => 'Cancelado'      // El elemento fue cancelado
         */

        ]);
        
        Log::info('Reporte principal creado exitosamente', [
            'id' => $reporte->idreporte_soluciones_clientes,
            'cliente_id' => $reporte->catalogo_clientes_idcatalogo_clientes,
            'tipo' => $reporte->catalogo_tipo_id,
            'estatus' => $reporte->estatus
        ]);
        
        // Guardar las facturas en lista_soluciones_clientes
        $facturasProcesadas = 0;
        foreach ($request->facturas as $index => $factura) {
            if (!empty($factura['catalogo_idcatalogo'])) { // <- valida que exista
                Log::info('Creando solución', [
                    'index' => $index,
                    'catalogo_id' => $factura['catalogo_idcatalogo'],
                    'factura' => $factura['factura'],
                    'cantidad' => $factura['cantidad'],
                    'total' => $factura['total']
                ]);
                
                $reporte->soluciones()->create([
                    'catalogo_soluciones_clientes_idCatalogoSolucionesClientes' => $factura['catalogo_idcatalogo'],
                    'factura' => $factura['factura'],
                    'cantidad' => $factura['cantidad'],
                    'total' => $factura['total'],
                    'observaciones' => $factura['observaciones'] ?? null,
                    'estatus' => 1, // Por defecto activo
                ]);
                
                $facturasProcesadas++;
            } else {
                Log::warning('Factura sin catálogo_idcatalogo omitida', ['index' => $index]);
            }
        }
        
        Log::info('Todas las soluciones han sido procesadas', [
            'total_procesadas' => $facturasProcesadas,
            'total_enviadas' => count($request->facturas)
        ]);
        Log::info('Creación de reporte completada exitosamente', ['id' => $reporte->idreporte_soluciones_clientes]);
        Log::info('**********************************************************************');

        return redirect()->route('soluciones.index')->with('success', 'Reporte creado correctamente.');
    }

    // Mostrar un reporte específico
    public function show($id)
    {
        $reporte = ReporteSolucionesCliente::with(['cliente', 'soluciones.catalogo'])->findOrFail($id);
        $operadores = Conductor::orderBy('operador')->get();
        return view('soluciones.show', compact('reporte', 'operadores'));
    }

    // Mostrar formulario de edición
    public function edit($id)
    {
        $reporte = ReporteSolucionesCliente::findOrFail($id);
        return view('soluciones.edit', compact('reporte'));
    }



    public function update(Request $request, $id)
    {
        Log::info("**********************************************************************");
        Log::info('Iniciando actualización de reporte de soluciones', ['id' => $id]);

        $reporte = ReporteSolucionesCliente::with('soluciones')->findOrFail($id);

        // Validación principal
        $request->validate([
            'catalogo_clientes_idcatalogo_clientes' => 'required|integer',
            'observaciones' => 'nullable|string|max:255',
            'estatus' => 'required|in:1,2,3,4,5,6,7',
            'facturas' => 'required|array|min:1',

            // Validación por cada fila
            'facturas.*.id' => 'nullable|integer',
            'facturas.*.catalogo_soluciones_clientes_idCatalogoSolucionesClientes' => 'required|integer',
            'facturas.*.factura' => 'required|string|max:100',
            'facturas.*.cantidad' => 'required|integer|min:1',
            'facturas.*.total' => 'required|numeric|min:0',
        ]);

        Log::info('Datos del reporte a actualizar:', $request->all());

        // Actualizar campos principales
        $reporte->update([
            'catalogo_clientes_idcatalogo_clientes' => $request->catalogo_clientes_idcatalogo_clientes,
            'observaciones' => $request->observaciones,
            'total' => $request->total,
            'descuento' => $request->descuento,
            'subtotal' => $request->subtotal,
            'iva' => $request->iva,
            'total_completo' => $request->total_completo,
            'estatus' => $request->estatus,
        ]);

        Log::info('Reporte actualizado correctamente', ['id' => $id]);

        // ==== ACTUALIZAR LISTA DE SOLUCIONES ====

        // IDs enviados por el formulario
        $idsEnviados = collect($request->facturas)
            ->pluck('id')
            ->filter()
            ->toArray();

        // IDs actuales en BD
        $idsBD = $reporte->soluciones->pluck('idlista_soluciones_clientes')->toArray();

        Log::info('IDs de soluciones', ['enviados' => $idsEnviados, 'en_bd' => $idsBD]);

        // Eliminar los que desaparecieron
        $eliminar = array_diff($idsBD, $idsEnviados);
        if (!empty($eliminar)) {
            Log::info('Eliminando soluciones', ['ids' => $eliminar]);
            ListaSolucionesCliente::whereIn('idlista_soluciones_clientes', $eliminar)->delete();
        }

        // Recorrer filas enviadas
        foreach ($request->facturas as $index => $fila) {

            // === Actualizar existente ===
            if (!empty($fila['id'])) {
                $sol = ListaSolucionesCliente::find($fila['id']);
                if ($sol) {
                    Log::info('Actualizando solución existente', ['id' => $fila['id'], 'datos' => $fila]);
                    $sol->update([
                        'factura' => $fila['factura'],
                        'cantidad' => $fila['cantidad'],
                        'total' => $fila['total'],
                        'observaciones' => $fila['observaciones'] ?? null,
                        'catalogo_soluciones_clientes_idCatalogoSolucionesClientes' =>
                        $fila['catalogo_soluciones_clientes_idCatalogoSolucionesClientes'],
                    ]);
                }
            } else {
                // === Crear nueva ===
                Log::info('Creando nueva solución', ['index' => $index, 'datos' => $fila]);
                $reporte->soluciones()->create([
                    'factura' => $fila['factura'],
                    'cantidad' => $fila['cantidad'],
                    'total' => $fila['total'],
                    'observaciones' => $fila['observaciones'] ?? null,
                    'catalogo_soluciones_clientes_idCatalogoSolucionesClientes' =>
                    $fila['catalogo_soluciones_clientes_idCatalogoSolucionesClientes'],
                ]);
            }
        }

        Log::info('Actualización de reporte completada exitosamente', ['id' => $id]);
        Log::info("**********************************************************************");
        return redirect()->route('soluciones.show', $reporte->idreporte_soluciones_clientes)
            ->with('success', 'Reporte actualizado correctamente.');
    }


    // Cambiar estatus del reporte
    public function cambiarEstatusSolucionesClientes(Request $request, $id)
    {
        $reporte = ReporteSolucionesCliente::findOrFail($id);
        $reporte->estatus = $request->input('estatus', 3); // 3 = Cancelado
        $reporte->observaciones = $request->input('observaciones', $reporte->observaciones);
        $reporte->save();
        return redirect()->route('soluciones.index', $reporte->idreporte_soluciones_clientes)
            ->with('success', 'Estatus actualizado correctamente.');
    }

    // Eliminar reporte
    public function destroy($id)
    {
        $reporte = ReporteSolucionesCliente::findOrFail($id);
        $reporte->delete();

        return redirect()->route('soluciones.index')->with('success', 'Reporte eliminado correctamente.');
    }

    public function firmar(Request $request, $id)
    {
        $reporte = ReporteSolucionesCliente::findOrFail($id);
        $campo = $request->input('campo');

        // Lista de campos válidos para firma
        $camposValidos = [
            'firma_soluciones' => 'firmas.Reporte Soluciones',
            'firma_credito' => 'firmas.Soluciones Credito',
        ];


        // Validar campo
        if (!array_key_exists($campo, $camposValidos)) {
            abort(403, 'Campo no permitido.');
        }

        // Validar permiso
        $permiso = $camposValidos[$campo];
        if (!auth()->user()->can($permiso)) {
            abort(403, 'Sin permiso para firmar.');
        }

        // Guardar el ID del usuario autenticado como firma
        $reporte->{$campo} = auth()->id();
        $reporte->save();

        return back()->with('success', 'Firma registrada correctamente.');
    }

    
}
