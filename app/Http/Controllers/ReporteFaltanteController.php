<?php

namespace App\Http\Controllers;

use App\Models\ReporteFaltante;
use Illuminate\Http\Request;

class ReporteFaltanteController extends Controller
{
    public function index()
    {
        // Traer reportes con paginación y relación a catalogo_faltante
        $reportes = ReporteFaltante::with('catalogoFaltante')->paginate(15);
        return view('reportes.reporte_faltante', compact('reportes'));
    }

    public function show($id)
    {
        $reporte = ReporteFaltante::with('reportesFalta.catalogoProducto')->findOrFail($id);
        return view('reportes.show_reporte_faltante', compact('reporte'));
    }
    public function edit($id)
    {
        $reporte = ReporteFaltante::findOrFail($id);
        return view('reportes.edit_reporte_faltante', compact('reporte'));
    }

    public function create()
    {
        $catalogoFaltantes = ReporteFaltante::all();
        return view('reportes.create_reporte_faltante', compact('catalogoFaltantes'));
    }

    public function store(Request $request)
    {
        // Validar datos principales del reporte
        $validated = $request->validate([
            'fecha' => 'required|date',
            'recibe_reporte' => 'nullable|string|max:45',
            'catalogo_faltante_idcatalogo_faltante' => 'required|integer|exists:catalogo_faltante,idcatalogo_faltante',
            'motivo_faltante' => 'nullable|string|max:70',
            'solucion' => 'nullable|string|max:45',
            'procede' => 'nullable|boolean',
            'cambio_fisico' => 'nullable|boolean',
            'nc_servicio' => 'nullable|boolean',
            'autorizo' => 'nullable|string|max:45',

            // Validación facturas
            'facturas' => 'required|array|min:1',
            'facturas.*.factura' => 'required|string|max:50',
            'facturas.*.icod' => 'nullable|string|max:45',
            'facturas.*.descripcion' => 'nullable|string|max:255',
            'facturas.*.p_unitario' => 'nullable|numeric',
            'facturas.*.cantidad' => 'nullable|numeric',
            'facturas.*.checo' => 'nullable|string|max:45',
            'facturas.*.empaco' => 'nullable|string|max:45',
            'facturas.*.catalogo_idcatalogo' => 'required|integer|exists:catalogo_producto,idcatalogoproducto',
        ]);

        // Guardar el reporte principal
        $reporte = ReporteFaltante::create([
            'fecha' => $validated['fecha'],
            'recibe_reporte' => $validated['recibe_reporte'] ?? null,
            'catalogo_faltante_idcatalogo_faltante' => $validated['catalogo_faltante_idcatalogo_faltante'],
            'motivo_faltante' => $validated['motivo_faltante'] ?? null,
            'solucion' => $validated['solucion'] ?? null,
            'procede' => $request->boolean('procede'),
            'cambio_fisico' => $request->boolean('cambio_fisico'),
            'nc_servicio' => $request->boolean('nc_servicio'),
            'user_id_registro'  => auth()->id(),
            'estatus' => 1, // Activo por defecto
        ]);

        // Guardar facturas asociadas
        foreach ($validated['facturas'] as $facturaData) {
            $reporte->reportesFalta()->create([
                'numero_factura' => $facturaData['factura'],
                'icod' => $facturaData['icod'] ?? null,
                'descripcion' => $facturaData['descripcion'] ?? null,
                'aiprecio' => $facturaData['p_unitario'] ?? null,
                'cantidad' => $facturaData['cantidad'] ?? null,
                'checo' => $facturaData['checo'] ?? null,
                'empaco' => $facturaData['empaco'] ?? null,
                'catalogo_idcatalogo' => $facturaData['catalogo_idcatalogo'], // ✅ columna correcta
                'reporte_faltante_idreporte_faltante' => $reporte->idreporte_faltante,
            ]);
        }

        return redirect()->route('reporte.faltante')
            ->with('success', 'Reporte creado correctamente con sus facturas.');
    }

    public function update(Request $request, $id)
    {
        $reporte = ReporteFaltante::findOrFail($id);

        // Validación
        $validated = $request->validate([
            'fecha' => 'required|date',
            'recibe_reporte' => 'nullable|string|max:45',
            'catalogo_faltante_idcatalogo_faltante' => 'required|integer|exists:catalogo_faltante,idcatalogo_faltante',
            'motivo_faltante' => 'nullable|string|max:70',
            'solucion' => 'nullable|string|max:45',
            'procede' => 'nullable|boolean',
            'cambio_fisico' => 'nullable|boolean',
            'nc_servicio' => 'nullable|boolean',
            'autorizo' => 'nullable|string|max:45',

            'facturas' => 'required|array|min:1',
            'facturas.*.factura' => 'required|string|max:50',
            'facturas.*.icod' => 'nullable|string|max:45',
            'facturas.*.descripcion' => 'nullable|string|max:255',
            'facturas.*.p_unitario' => 'nullable|numeric',
            'facturas.*.cantidad' => 'nullable|numeric',
            'facturas.*.checo' => 'nullable|string|max:45',
            'facturas.*.empaco' => 'nullable|string|max:45',
            'facturas.*.catalogo_idcatalogo' => 'nullable|integer|exists:catalogo_producto,idcatalogoproducto',
            'facturas.*.id' => 'nullable|integer|exists:reportes_falta,id',
        ]);

        // Actualizar datos principales
        $reporte->update($validated);

        // IDs enviados desde el formulario
        $facturasIdsForm = collect($validated['facturas'])->pluck('id')->filter()->toArray();

        // Borrar facturas eliminadas
        $reporte->reportesFalta()->whereNotIn('id', $facturasIdsForm)->delete();

        // Actualizar o insertar facturas
        foreach ($validated['facturas'] as $facturaData) {
            if (isset($facturaData['id'])) {
                // Actualizar existente
                $factura = $reporte->reportesFalta()->find($facturaData['id']);
                if ($factura) {
                    $factura->update([
                        'numero_factura' => $facturaData['factura'],
                        'icod' => $facturaData['icod'] ?? null,
                        'descripcion' => $facturaData['descripcion'] ?? null,
                        'aiprecio' => $facturaData['p_unitario'] ?? null,
                        'cantidad' => $facturaData['cantidad'] ?? null,
                        'checo' => $facturaData['checo'] ?? null,
                        'empaco' => $facturaData['empaco'] ?? null,
                        'catalogo_idcatalogo' => $facturaData['catalogo_idcatalogo'] ?? null,
                    ]);
                }
            } else {
                // Crear nueva
                $reporte->reportesFalta()->create([
                    'numero_factura' => $facturaData['factura'],
                    'icod' => $facturaData['icod'] ?? null,
                    'descripcion' => $facturaData['descripcion'] ?? null,
                    'aiprecio' => $facturaData['p_unitario'] ?? null,
                    'cantidad' => $facturaData['cantidad'] ?? null,
                    'checo' => $facturaData['checo'] ?? null,
                    'empaco' => $facturaData['empaco'] ?? null,
                    'catalogo_idcatalogo' => $facturaData['catalogo_idcatalogo'] ?? null,
                ]);
            }
        }

        return redirect()->route('reporte.faltante')->with('success', 'Reporte actualizado correctamente.');
    }


    public function cambiarEstatus(Request $request, $id)
    {
        $reporte = ReporteFaltante::findOrFail($id);
        $reporte->estatus = 0;
        $reporte->observaciones = $request->input('observaciones'); // Agrega observaciones
        $reporte->save();
        return redirect()->route('reporte.faltante')->with('success', 'Reporte desactivado correctamente.');
    }

    public function autorizacionFirma($id)
    {
        $reporte = ReporteFaltante::findOrFail($id);
        $reporte->user_id_autorizo = auth()->id(); // Guarda el id del usuario que autoriza
        $reporte->save();
        return redirect()->back()->with('success', 'Reporte autorizado correctamente.');
    }
}
