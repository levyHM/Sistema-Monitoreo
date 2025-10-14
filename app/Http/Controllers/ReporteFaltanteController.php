<?php

namespace App\Http\Controllers;

use App\Models\CatalogoMotivoFaltante;
use App\Models\CatalogoReporteFaltanteTipo;
use App\Models\ReporteFaltante;
use Illuminate\Http\Request;

class ReporteFaltanteController extends Controller
{
    public function index(Request $request)
    {
        $query = ReporteFaltante::with('catalogoFaltante')->orderByDesc('idreporte_faltante');
        $tipos = CatalogoReporteFaltanteTipo::where('estatus', 1)->get();

        // Filtro por motivo
        if ($request->filled('motivo_id')) {
            $query->where('motivo_faltante_id', $request->motivo_id);
        }

        // Filtro por solución 
        if ($request->filled('catalogo_reporte_faltante_tipo_id')) {
            $query->where('catalogo_reporte_faltante_tipo_id', $request->catalogo_reporte_faltante_tipo_id);
        }


        // Filtro por estatus
        if ($request->filled('estatus')) {
            $query->where('estatus', $request->estatus);
        }

        $reportes = $query->paginate(10)->appends($request->query());

        // Para el select de motivos en el formulario
        $motivos = CatalogoMotivoFaltante::where('estatus', 1)->get();

        return view('reportes.reporte_faltante', compact('reportes', 'motivos', 'tipos'));
    }


    public function show($id)
    {
        $reporte = ReporteFaltante::with('reportesFalta.catalogoProducto')->findOrFail($id);
        return view('reportes.show_reporte_faltante', compact('reporte'));
    }
    public function edit($id)
    {
        $reporte = ReporteFaltante::findOrFail($id);
        $motivos = CatalogoMotivoFaltante::where('estatus', 1)->get();

        return view('reportes.edit_reporte_faltante', compact('reporte', 'motivos'));
    }

    public function create()
    {
        $catalogoFaltantes = ReporteFaltante::all();
        $motivos = CatalogoMotivoFaltante::where('estatus', 1)->get(); // Para el select de motivos

        return view('reportes.create_reporte_faltante', compact('catalogoFaltantes', 'motivos'));
    }

    public function store(Request $request)
    {
        // Validar datos principales del reporte
        $validated = $request->validate([
            'fecha' => 'required|date',
            'recibe_reporte' => 'nullable|string|max:45',
            'catalogo_faltante_idcatalogo_faltante' => 'required|integer|exists:catalogo_faltante,idcatalogo_faltante',
            'motivo_faltante_id' => 'nullable|integer|exists:catalogo_motivo_faltante,id',
            'solucion' => 'nullable|string|max:45',
            'catalogo_reporte_faltante_tipo_id' => 'required|in:1,2,3,4',
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
            'motivo_faltante_id' => $validated['motivo_faltante_id'] ?? 0,
            'solucion' => $validated['solucion'] ?? null,
            'catalogo_reporte_faltante_tipo_id' => $validated['catalogo_reporte_faltante_tipo_id'],
            'user_id_registro'  => auth()->id(),
            'estatus' => 2, // Activo por defecto Pendiente
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

        return redirect()->route('reporte.faltante')->with('success', 'Reporte creado correctamente con sus facturas.');
    }

    public function update(Request $request, $id)
    {
        $reporte = ReporteFaltante::findOrFail($id);

        // Validación
        $validated = $request->validate([
            'fecha' => 'required|date',
            'recibe_reporte' => 'nullable|string|max:45',
            'catalogo_faltante_idcatalogo_faltante' => 'required|integer|exists:catalogo_faltante,idcatalogo_faltante',
            'motivo_faltante_id' => 'nullable|integer|exists:catalogo_motivo_faltante,id',
            'solucion' => 'nullable|string|max:45',
            'catalogo_reporte_faltante_tipo_id' => 'required|in:1,2,3,4',
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

        return redirect()->route('reporte.faltante.show', $reporte->idreporte_faltante)->with('success', 'Reporte actualizado correctamente.');
    }


    public function cambiarEstatus(Request $request, $id)
    {
        $reporte = ReporteFaltante::findOrFail($id);
        $reporte->estatus = 3;
        $reporte->observaciones = $request->input('observaciones'); // Agrega observaciones
        $reporte->save();
        return redirect()->route('reporte.faltante')->with('success', 'Reporte desactivado correctamente.');
    }

    public function autorizacionFirma($id)
    {
        $reporte = ReporteFaltante::findOrFail($id);
        $reporte->user_id_autorizo = auth()->id(); // Guarda el id del usuario que autoriza
        $reporte->estatus = 1; // Cambia el estatus a autorizado
        $reporte->save();
        return redirect()->back()->with('success', 'Reporte autorizado correctamente.');
    }
}
