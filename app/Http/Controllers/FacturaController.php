<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Factura;

class FacturaController extends Controller
{
    public function index(Request $request)
    {
        // Crear la consulta base con el filtro DITIPMV = 'FE'
        $query = Factura::where('DITIPMV', 'FE');

        // Filtrar por captura si se proporciona
        if ($request->filled('captura')) {
            $query->where('CAPTURA', 'like', '%' . $request->captura . '%');
        }

        // Filtrar por estatus si se selecciona (0 = Pendiente, 1 = Validado)
        if ($request->filled('estatus')) {
            $query->where('ESTATUS', $request->estatus);
        }

        // Obtener los resultados paginados
        $facturas = $query->orderBy('id', 'desc')->paginate(100);

        // Retornar la vista con los datos filtrados
        return view('pages.facturas-cdmx', compact('facturas'));
    }

    public function filter(Request $request)
    {
        $estatus = $request->input('estatus');

        // Filtrar facturas según el estado seleccionado
        $facturas = Factura::where('DITIPMV', 'FE') // Puedes agregar más filtros si es necesario
            ->when($estatus == '1', function ($query) {
                return $query->where('ESTATUS', 1);
            })
            ->when($estatus == '0', function ($query) {
                return $query->where('ESTATUS', 0);
            })
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($facturas);
    }


    public function facturasOaxaca(Request $request)
    {
        // Filtrar las facturas específicamente para Oaxaca si es necesario
        $facturas = Factura::where('DITIPMV', 'FO')  // Puedes agregar otros filtros si es necesario
            ->orderBy('id', 'desc')
            ->paginate(100);
        
        return view('pages.facturas-oaxaca', compact('facturas'));
    }

    public function facturasXalapa(Request $request)
    {
        // Filtrar las facturas específicamente para Oaxaca si es necesario
        $facturas = Factura::where('DITIPMV', 'FV')  // Puedes agregar otros filtros si es necesario
            ->orderBy('id', 'desc')
            ->paginate(100);
        
        return view('pages.facturas-xalapa', compact('facturas'));
    }

    public function create()
    {
        return view('facturas.create');
    }

    public function store(Request $request)
    {
        // Validar la solicitud
        $validatedData = $request->validate([
            'captura' => 'required|string', // "captura" es obligatorio y de tipo string
        ]);
    
        try {
            // Buscar el registro por el campo "SERIE"
        $empacado = Factura::where('SERIE', $validatedData['captura'])->first();

        if ($empacado) {
                // Verificar si ya tiene el mismo valor para evitar una actualización innecesaria
                if ($empacado->CAPTURA === $validatedData['captura'] && $empacado->ESTATUS == 1) {
                    logger('Registro duplicado: ' . $empacado->id);
                    //return redirect()->route('facturas.index')->with('warning', 'El registro ya existe y está actualizado.');
                    return $this->redirectBackWithMessage('warning', 'El registro ya existe y está actualizado.');
                }
    
            // Si el registro existe, se actualiza
            $empacado->update([
                'CAPTURA' => $validatedData['captura'],
                'ESTATUS' => '1',
            ]);
    
                logger('Registro actualizado: ' . $empacado->id);
                //return redirect()->route('facturas.index')->with('success', 'Registro actualizado exitosamente.');
                return $this->redirectBackWithMessage('success', 'Registro actualizado exitosamente.');
        } else {
                // Si el registro no existe
            logger('No se encontró el registro con el número de captura: ' . $validatedData['captura']);
                //return redirect()->route('facturas.index')->with('error', 'No existe el registro con el número de captura proporcionado.');
                return $this->redirectBackWithMessage('error', 'No existe el registro con el número de captura proporcionado.');
            }
        } catch (\Exception $e) {
            logger('Error en la operación: ' . $e->getMessage());
            //return redirect()->route('facturas.index')->with('error', 'Ocurrió un error al procesar la solicitud.');
            return $this->redirectBackWithMessage('error', 'Ocurrió un error al procesar la solicitud.');
        }
    }

        // Método auxiliar para manejar la redirección dinámica
        private function redirectBackWithMessage($type, $message)
        {
            return redirect()->back()->with($type, $message);
    }
    
    public function show($id)
    {
        $factura = Factura::find($id);
        return view('facturas.show', compact('factura'));
    }

    public function edit($id)
    {
        $factura = Factura::find($id);
        return view('facturas.edit', compact('factura'));
    }

 
    public function update(Request $request, $id)
    {
        $empacado = Factura::findOrFail($id);
        $empacado->update($request->all());
        return redirect()->route('facturas.index')->with('success', 'Actualizado exitosamente');
    }

    public function destroy($id)
    {
        $factura = Factura::find($id);
        $factura->delete();

        return redirect()->route('facturas.index')
            ->with('success', 'Factura eliminada con éxito.');
    }
}
