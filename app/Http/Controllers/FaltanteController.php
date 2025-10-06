<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recibo;
use App\Models\CatalogoProveedor;
use App\Models\Firma;
use Illuminate\Support\Facades\Log;
use App\Models\ConceptoRecibo;
use App\Models\Producto;
use App\Models\Evidencia;
use App\Models\Concepto;

class FaltanteController extends Controller
{
    public function index(Request $request)
    {
        $proveedores = CatalogoProveedor::all();

        $query = Recibo::with('proveedor')
            ->where('tipo_recibo', 'F')
            ->orderByDesc('idrecibos');

        // Filtros dinámicos
        if ($request->filled('fecha')) {
            $query->whereDate('fecha', $request->fecha);
        }

        if ($request->filled('proveedor')) {
            $query->whereHas('proveedor', function ($q) use ($request) {
                $q->where('prvcod', 'like', "%{$request->proveedor}%")
                    ->orWhere('prvnom', 'like', "%{$request->proveedor}%");
            });
        }

        if ($request->filled('numero_factura')) {
            $query->whereHas('conceptos', function ($q) use ($request) {
                $q->where('numero_factura', 'like', "%{$request->numero_factura}%");
            });
        }

        if ($request->filled('estatus')) {
            $query->where('estatus', $request->estatus);
        }

        $recibos = $query->paginate(100)->appends($request->query());

        return view('faltantes.cdmx.index', compact('recibos', 'proveedores'));
    }


    public function create()
    {
        $proveedores = CatalogoProveedor::all();
        $recibos = Recibo::with('proveedor')->get();
        return view('faltantes.cdmx.create', compact('proveedores', 'recibos'));
    }

    public function store(Request $request)
    {
        Log::info('Creando nuevo recibo...');
        Log::info('Request recibido:', $request->all());
        $request->validate([
            'tipo_recibo' => 'required|in:D,F',
            'sucursal' => 'required|in:F,PV,PO',
            'provedores_idprovedores' => 'required|exists:catalogo_provedores,idcatalogo_provedores',
            'facturas' => 'required|array|min:1',
            'facturas.*.factura' => 'required|string',
            'facturas.*.cantidad' => 'required|integer|min:1',
            'facturas.*.no_parte' => 'nullable|string',
            'facturas.*.descripcion' => 'nullable|string',
            'facturas.*.facturado' => 'nullable|string',
            'facturas.*.fisico' => 'nullable|string',
            'evidencias' => 'nullable|array|max:3',
            'evidencias.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // max 2MB por imagen
            'faltante' => 'nullable|boolean',
            'sobrante' => 'nullable|boolean',

        ]);
        $recibo = Recibo::create([
            'tipo_recibo' => $request->input('tipo_recibo'),
            'sucursal' => $request->input('sucursal'),
            'fecha' => now(),
            'estatus' => 'activo', // o el estatus que desees
            'provedores_idprovedores' => $request->input('provedores_idprovedores'),
        ]);

        Log::info('Recibo creado con ID: ' . $recibo->idrecibos);
        // Agregar el id del recibo al request
        $request->merge(['recibos_idrecibos' => $recibo->idrecibos]);

        // Crear la firma asociada al recibo
        $firma = Firma::create([
            'id_user' => $request->input('usuario'),
            'catalogo_firma_idcatalogo_firma' => 1,
            'observaciones' => "",
            'estatus' => 1,
            'recibos_idrecibos' => $recibo->idrecibos,
        ]);

        Log::info('Firma registrada correctamente con ID: ' . $firma->idfirma);

        //concepto devolucion

        $devolucion = Concepto::create([
            'devolucion' => 0,
            'faltante' => $request->input('faltante') ? 1 : 0, // Asignar faltante según el request
            'sobrante' => $request->input('sobrante') ? 1 : 0,
            'recibos_idrecibos' => $recibo->idrecibos,
        ]);

        Log::info('Concepto de devolución creado con ID: ' . $devolucion->idconceptos);

        // Insertar facturas en concepto_recibos
        foreach ($request->input('facturas') as $factura) {
            // Buscar el producto por código (no_parte), asegúrate que existe en tabla productos
            $producto = Producto::where('icod', $factura['no_parte'])->first();

            if (!$producto) {
                throw new \Exception("Producto con ICOD {$factura['no_parte']} no encontrado.");
            }

            ConceptoRecibo::create([
                'numero_factura' => $factura['factura'],
                'cantidad' => $factura['cantidad'],
                'productos_idproductos' => $producto->idproductos,
                'recibos_idrecibos' => $recibo->idrecibos,
                'facturado' => $factura['facturado'] ?? null, // puedes asignar según lógica
                'fisico' => $factura['fisico'] ?? null,    // puedes asignar según lógica
                'observaciones' => null
            ]);

            Log::info("Factura insertada: {$factura['factura']} para producto {$producto->icod}");
        }

        // Guardar evidencias (imágenes)
        if ($request->hasFile('evidencias')) {
            foreach ($request->file('evidencias') as $imagen) {
                try {
                    // Guardar archivo en carpeta pública 'evidencias'
                    $ruta = $imagen->store('evidencias/' . $recibo->idrecibos, 'public');

                    Evidencia::create([
                        'url' => $ruta, // guarda la ruta relativa
                        'estatus' => 1,
                        'recibos_idrecibos' => $recibo->idrecibos,
                    ]);

                    Log::info("Evidencia guardada: {$ruta}");
                } catch (\Exception $e) {
                    Log::error("Error al guardar evidencia: " . $e->getMessage());
                }
            }
        }

        return redirect()->route('faltantes.cdmx.index')->with('success', 'Recibo registrado correctamente.');
    }

    public function edit(Recibo $recibo)
    {
        // Cargar todos los proveedores
        $proveedores = CatalogoProveedor::all();

        // Cargar recibo con relaciones necesarias para la edición
        $recibo = Recibo::with(['proveedor', 'conceptos.producto', 'evidencias', 'firma', 'conceptosEstado'])
            ->findOrFail($recibo->idrecibos);

        // Retornar la vista 'recibos.edit' (asegúrate que coincida con tu archivo Blade)
        return view('faltantes.cdmx.edit', compact('recibo', 'proveedores'));
    }

    public function update(Request $request, Recibo $recibo)
    {
        try {
            $request->validate([
                'tipo_recibo' => 'required|in:D,F',
                'sucursal' => 'required|in:F,PV,PO',
                'provedores_idprovedores' => 'required|exists:catalogo_provedores,idcatalogo_provedores',
                'facturas' => 'required|array|min:1',
                'facturas.*.factura' => 'required|string',
                'facturas.*.cantidad' => 'required|integer|min:1',
                'facturas.*.no_parte' => 'nullable|string',
                'facturas.*.descripcion' => 'nullable|string',
                'facturas.*.facturado' => 'nullable|string',
                'facturas.*.fisico' => 'nullable|string',
                'evidencias' => 'nullable|array|max:3',
                'evidencias.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'faltante' => 'nullable|boolean',
                'sobrante' => 'nullable|boolean',
            ]);

            // Actualizar datos del recibo
            $recibo->update([
                'tipo_recibo' => $request->input('tipo_recibo'),
                'sucursal' => $request->input('sucursal'),
                'provedores_idprovedores' => $request->input('provedores_idprovedores'),
            ]);

            // Actualizar devolución en Concepto (asumiendo solo un concepto de devolución)
            $conceptoDevolucion = $recibo->conceptosEstado()->first();
            if ($conceptoDevolucion) {
                $conceptoDevolucion->update([
                    'devolucion' => $request->input('devolucion') ? 1 : 0,
                    'faltante' => $request->input('faltante') ? 1 : 0,
                    'sobrante' => $request->input('sobrante') ? 1 : 0,
                ]);
            } else {
                Concepto::create([
                    'devolucion' => 0,
                    'faltante' => $request->input('faltante') ? 1 : 0,
                    'sobrante' => $request->input('sobrante') ? 1 : 0,
                    'recibos_idrecibos' => $recibo->idrecibos,
                ]);
            }

            // Actualizar o eliminar facturas (concepto_recibos)
            ConceptoRecibo::where('recibos_idrecibos', $recibo->idrecibos)->delete();

            foreach ($request->input('facturas') as $factura) {
                $producto = Producto::where('icod', $factura['no_parte'])->first();
                if (!$producto) {
                    throw new \Exception("Producto con ICOD {$factura['no_parte']} no encontrado.");
                }

                ConceptoRecibo::create([
                    'numero_factura' => $factura['factura'],
                    'cantidad' => $factura['cantidad'],
                    'productos_idproductos' => $producto->idproductos,
                    'recibos_idrecibos' => $recibo->idrecibos,
                    'facturado' => $factura['facturado'] ?? null,
                    'fisico' => $factura['fisico'] ?? null,
                    'observaciones' => null,
                ]);
            }

            // Manejo de evidencias (imágenes)
            if ($request->hasFile('evidencias')) {
                Evidencia::where('recibos_idrecibos', $recibo->idrecibos)->delete();

                foreach ($request->file('evidencias') as $imagen) {
                    $ruta = $imagen->store('evidencias/' . $recibo->idrecibos, 'public');

                    Evidencia::create([
                        'url' => $ruta,
                        'estatus' => 1,
                        'recibos_idrecibos' => $recibo->idrecibos,
                    ]);
                }
            }

            return redirect()->route('faltantes.cdmx.index')->with('success', 'Recibo actualizado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al actualizar recibo: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }


    public function show(Recibo $recibo)
    {
        // Cargar las relaciones del recibo específico
        $recibo->load([
            'proveedor',
            'firma.usuario',
            'conceptos.producto',
            'evidencias'
        ]);
        Log::info('Mostrando recibo con ID: ' . $recibo->idrecibos);
        Log::info('Recibo detalles:', $recibo->toArray());
        Log::info('Proveedor ID:', ['id' => $recibo->provedores_idprovedores]);

        return view('faltantes.cdmx.show', compact('recibo'));
    }

    public function cancelar(Request $request, $id)
    {
        $request->validate([
            'observaciones' => 'required|string|max:255',
        ]);

        $recibo = Recibo::findOrFail($id);

        $recibo->estatus = 'cancelado';
        $recibo->observaciones = $request->observaciones;
        $recibo->save();
        Log::info('Request de cancelación de devolución:', $request->all());
        Log::info('Recibo después de cancelar devolución:', $recibo->toArray());
        return redirect()->route('faltantes.cdmx.index')->with('success', 'Devolución cancelada correctamente.');
    }


    public function destroy(Recibo $recibo)
    {
        $recibo->delete();
        return redirect()->route('recibos.index')->with('success', 'Recibo eliminado.');
    }
}
