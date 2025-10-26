<?php

namespace App\Http\Controllers;

use App\Models\CatalogoTipo;
use App\Models\ReporteSolucionesCliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        $tipo = $request->tipo ?? 1; // Por defecto Garantía
        return view('soluciones.create', compact('tipo'));
    }

    // Guardar nuevo reporte
    public function store(Request $request)
    {
        Log::info('Guardando nuevo reporte de soluciones para cliente.');
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
         * 3 => 'En Recolección'      // El elemento está en proceso de dictamen.
         * 4 => 'En Almacén'      // El elemento no fue aprobado.
         * 5 => 'En Dictamen'      // El elemento fue aprobado y está listo para ser entregado.
         * 6 => 'Cancelado'        // El elemento fue cancelado.
         */

        ]);
        // Guardar las facturas en lista_soluciones_clientes
        foreach ($request->facturas as $factura) {
            if (!empty($factura['catalogo_idcatalogo'])) { // <- valida que exista
                $reporte->soluciones()->create([
                    'catalogo_soluciones_clientes_idCatalogoSolucionesClientes' => $factura['catalogo_idcatalogo'],
                    'factura' => $factura['factura'],
                    'cantidad' => $factura['cantidad'],
                    'total' => $factura['total'],
                    'observaciones' => $factura['observaciones'] ?? null,
                    'estatus' => 1, // Por defecto activo
                ]);
            }
        }

        return redirect()->route('soluciones.index')->with('success', 'Reporte creado correctamente.');
    }

    // Mostrar un reporte específico
    public function show($id)
    {
        $reporte = ReporteSolucionesCliente::with(['cliente', 'soluciones.catalogo'])->findOrFail($id);

        return view('soluciones.show', compact('reporte'));
    }

    // Mostrar formulario de edición
    public function edit($id)
    {
        $reporte = ReporteSolucionesCliente::findOrFail($id);
        return view('soluciones.edit', compact('reporte'));
    }

    public function update(Request $request, $id)
    {
        $reporte = ReporteSolucionesCliente::with('soluciones')->findOrFail($id);

        // Validación
        $request->validate([
            'catalogo_clientes_idcatalogo_clientes' => 'required|integer',
            'devolucion' => 'nullable|boolean',
            'garantia' => 'nullable|boolean',
            'observaciones' => 'nullable|string|max:255',
            'total' => 'nullable|numeric',
            'descuento' => 'nullable|numeric',
            'subtotal' => 'nullable|numeric',
            'iva' => 'nullable|numeric',
            'total_completo' => 'nullable|numeric',
            'estatus' => 'required|in:1,2,3,4,5,6',
            'facturas' => 'required|array|min:1',
            'facturas.*.catalogo_soluciones_clientes_idCatalogoSolucionesClientes' => 'required|integer',
            'facturas.*.factura' => 'required|string|max:100',
            'facturas.*.cantidad' => 'required|integer|min:1',
            'facturas.*.total' => 'required|numeric|min:0',
        ]);

        // Actualizar campos del reporte
        $reporte->update([
            'catalogo_clientes_idcatalogo_clientes' => $request->catalogo_clientes_idcatalogo_clientes,
            'devolucion' => $request->has('devolucion') ? 1 : 0,           
            'observaciones' => $request->observaciones,
            'total' => $request->total,
            'descuento' => $request->descuento,
            'subtotal' => $request->subtotal,
            'iva' => $request->iva,
            'total_completo' => $request->total_completo,
            'estatus' => $request->input('estatus'),

        ]);

        // PK real de la relación soluciones
        $pk = $reporte->soluciones()->getRelated()->getKeyName();

        // Sincronizar facturas
        $facturasInput = $request->input('facturas', []);
        $facturaIds = [];

        foreach ($facturasInput as $facturaData) {
            if (isset($facturaData['catalogo_soluciones_clientes_idCatalogoSolucionesClientes']) && $facturaData['catalogo_soluciones_clientes_idCatalogoSolucionesClientes']) {

                if (!empty($facturaData[$pk])) {
                    $factura = $reporte->soluciones()->find($facturaData[$pk]);
                    if ($factura) {
                        $factura->update($facturaData);
                        $facturaIds[] = $factura->{$pk};
                    }
                } else {
                    $nueva = $reporte->soluciones()->create($facturaData);
                    $facturaIds[] = $nueva->{$pk};
                }
            } else {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'facturas' => 'Selecciona un ICOD válido para todas las facturas.'
                ]);
            }
        }

        // Eliminar facturas removidas
        if (count($facturaIds)) {
            $reporte->soluciones()->whereNotIn($pk, $facturaIds)->delete();
        } else {
            $reporte->soluciones()->delete();
        }

        return redirect()->route('soluciones.show', $reporte->idreporte_soluciones_clientes)
            ->with('success', 'Reporte actualizado correctamente.');
    }

    // Cambiar estatus del reporte
    public function cambiarEstatusSolucionesClientes(Request $request, $id)
    {
        $reporte = ReporteSolucionesCliente::findOrFail($id);
        $reporte->estatus = $request->input('estatus', 6); // 6 = Cancelado
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
