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

class ReciboController extends Controller
{
    public function index()
    {
        $proveedores = CatalogoProveedor::all();
        $recibos = Recibo::with('proveedor')
            ->where('tipo_recibo', 'D')
            ->orderByDesc('idrecibos')
            ->paginate(100);
        return view('recibos.index', compact('recibos', 'proveedores'));
    }

    public function create()
    {
        $proveedores = CatalogoProveedor::all();
        $recibos = Recibo::with('proveedor')->get();
        return view('recibos.create', compact('proveedores', 'recibos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo_recibo' => 'required|in:D,F',
            'sucursal' => 'required|in:P,PV,PO',
            'provedores_idprovedores' => 'required|exists:catalogo_provedores,idcatalogo_provedores',
            'facturas' => 'required|array|min:1',
            'facturas.*.factura' => 'required|string',
            'facturas.*.cantidad' => 'required|integer|min:1',
            'facturas.*.no_parte' => 'nullable|string',
            'facturas.*.descripcion' => 'nullable|string',
            'facturas.*.observaciones' => 'nullable|string',
            'evidencias' => 'nullable|array|max:3',
            'evidencias.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // max 2MB por imagen
            'devolucion' => 'nullable|boolean',

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
            'devolucion' => $request->input('devolucion') ? 1 : 0,
            'faltante' => 0,
            'sobrante' => 0,
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
                'facturado' => null, // puedes asignar según lógica
                'fisico' => null,    // puedes asignar según lógica
                'observaciones' => $factura['observaciones'] ?? null,
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

        return redirect()->route('recibos.index')->with('success', 'Recibo registrado correctamente.');
    }

    public function edit(Recibo $recibo)
    {
        // Cargar todos los proveedores
        $proveedores = CatalogoProveedor::all();

        // Cargar recibo con relaciones necesarias para la edición
        $recibo = Recibo::with(['proveedor', 'conceptos.producto', 'evidencias', 'firma', 'conceptosEstado'])
            ->findOrFail($recibo->idrecibos);

        // Retornar la vista 'recibos.edit' (asegúrate que coincida con tu archivo Blade)
        return view('recibos.edit', compact('recibo', 'proveedores'));
    }


    public function update(Request $request, Recibo $recibo)
    {
        Log::info('Actualizando recibo ID: ' . $request);
        $request->validate([
            'tipo_recibo' => 'required|in:D,F',
            'sucursal' => 'required|in:F,PO',
            'provedores_idprovedores' => 'required|exists:catalogo_provedores,idcatalogo_provedores',
            'facturas' => 'required|array|min:1',
            'facturas.*.factura' => 'required|string',
            'facturas.*.cantidad' => 'required|integer|min:1',
            'facturas.*.no_parte' => 'nullable|string',
            'facturas.*.descripcion' => 'nullable|string',
            'facturas.*.observaciones' => 'nullable|string',
            'evidencias' => 'nullable|array|max:3',
            'evidencias.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'devolucion' => 'nullable|boolean',
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
            ]);
            Log::info('Concepto de devolución actualizado para recibo ID: ' . $recibo->idrecibos);
        } else {
            // Si no existe, crear uno nuevo
            Concepto::create([
                'devolucion' => $request->input('devolucion') ? 1 : 0,
                'faltante' => 0,
                'sobrante' => 0,
                'recibos_idrecibos' => $recibo->idrecibos,
            ]);
            Log::info('Concepto de devolución creado para recibo ID: ' . $recibo->idrecibos);
        }

        // Actualizar o eliminar facturas (concepto_recibos)
        // Lo más seguro: eliminar las anteriores y agregar las nuevas para simplificar
        ConceptoRecibo::where('recibos_idrecibos', $recibo->idrecibos)->delete();

        foreach ($request->input('facturas') as $factura) {
            $producto = Producto::where('icod', $factura['no_parte'])->first();
            if (!$producto) {
                return redirect()->back()->withErrors(['facturas' => "Producto con ICOD {$factura['no_parte']} no encontrado."])->withInput();
            }

            ConceptoRecibo::create([
                'numero_factura' => $factura['factura'],
                'cantidad' => $factura['cantidad'],
                'productos_idproductos' => $producto->idproductos,
                'recibos_idrecibos' => $recibo->idrecibos,
                'facturado' => null,
                'fisico' => null,
                'observaciones' => $factura['observaciones'] ?? null,
            ]);
        }

        // Manejo de evidencias (imágenes)
        if ($request->hasFile('evidencias')) {
            // Opcional: eliminar evidencias previas si quieres reemplazar
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

        return redirect()->route('recibos.index')->with('success', 'Recibo actualizado correctamente.');
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

        return view('recibos.show', compact('recibo'));
    }

    public function cancelarDevolucion(Request $request, $id)
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
        return redirect()->route('recibos.index')->with('success', 'Devolución cancelada correctamente.');
    }


    public function destroy(Recibo $recibo)
    {
        $recibo->delete();
        return redirect()->route('recibos.index')->with('success', 'Recibo eliminado.');
    }

    public function firmarRecibo(Request $request, $reciboId)
    {
        $user = auth()->user();
        Log::info("Intentando firmar recibo ID: {$reciboId} por usuario ID: {$user->id}");

        // Validar que se envíe el área (ID entero)
        $request->validate([
            'catalogo_firma_idcatalogo_firma' => 'required|integer',
        ]);

        $areaId = $request->input('catalogo_firma_idcatalogo_firma');

        // Mapear permisos según área (IDs ya definidos en tu sistema)
        $mapaPermisos = [
            2 => 'firmas.Soluciones',
            3 => 'firmas.Almacen',
            4 => 'firmas.Compras',
            5 => 'firmas.Proveedor',
            // Recepción ya está formada, y si no requiere firma o permiso, se omite
        ];

        $permiso = $mapaPermisos[$areaId] ?? null;

        // Validar permiso específico
        if (!$permiso || !$user->can($permiso)) {
            Log::warning("Usuario ID: {$user->id} no tiene permiso {$permiso} para firmar el recibo ID: {$reciboId}");
            return redirect()->back()->with('error', 'No tienes permiso para firmar esta área.');
        }

        try {
            // Registrar la firma
            Firma::create([
                'id_user' => $user->id,
                'catalogo_firma_idcatalogo_firma' => $areaId,
                'observaciones' => '',
                'estatus' => 1,
                'recibos_idrecibos' => $reciboId,
            ]);

            Log::info("Firma registrada correctamente en área {$areaId} para recibo ID: {$reciboId}, usuario ID: {$user->id}");
            return redirect()->back()->with('success', 'Firma registrada correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al firmar recibo ID {$reciboId}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al registrar la firma.');
        }
    }
}
