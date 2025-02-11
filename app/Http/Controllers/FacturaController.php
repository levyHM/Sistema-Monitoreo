<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Factura;

class FacturaController extends Controller
{
    public function index(Request $request)
    {
        // Filtrar las facturas específicamente para Oaxaca si es necesario
        $facturas = Factura::where('DITIPMV', 'FE')  // Puedes agregar otros filtros si es necesario
            ->orderBy('id', 'desc')
            ->paginate(100);
        return view('pages.facturas-cdmx', compact('facturas'));
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
    
        // Buscar el registro por el campo captura
        $empacado = Factura::where('SERIE', $validatedData['captura'])->first();
        logger('Busqueda: ' .$empacado);
        if ($empacado) {
            // Verificar si se encontró el registro
            logger('Registro encontrado: ' . $empacado->id);
    
            // Si el registro existe, se actualiza
            $empacado->update([
                'CAPTURA' => $validatedData['captura'],
                'ESTATUS' => '1',
            ]);
    
            // Verificar si la actualización se realizó correctamente
            logger('Registro actualizado: ' . $empacado->captura);
        } else {
            // Registrar que no se encontró el registro
            logger('No se encontró el registro con el número de captura: ' . $validatedData['captura']);
            
            return redirect()->route('facturas.index')->with('error', 'No existe el registro con el número de captura proporcionado.');
        }
    
        return redirect()->route('facturas.index')->with('success', 'Registro actualizado o creado exitosamente.');
    }

    public function storeXalapa(Request $request)
    {
        // Validar la solicitud
        $validatedData = $request->validate([
            'captura' => 'required|string', // "captura" es obligatorio y de tipo string
        ]);
    
        // Buscar el registro por el campo captura
        $empacado = Factura::where('SERIE', $validatedData['captura'])->first();
        logger('Busqueda: ' .$empacado);
        if ($empacado) {
            // Verificar si se encontró el registro
            logger('Registro encontrado: ' . $empacado->id);
    
            // Si el registro existe, se actualiza
            $empacado->update([
                'CAPTURA' => $validatedData['captura'],
                'ESTATUS' => '1',
            ]);
    
            // Verificar si la actualización se realizó correctamente
            logger('Registro actualizado: ' . $empacado->captura);
        } else {
            // Registrar que no se encontró el registro
            logger('No se encontró el registro con el número de captura: ' . $validatedData['captura']);
            
            return redirect()->route('facturas-xalapa.index')->with('error', 'No existe el registro con el número de captura proporcionado.');
        }
    
        return redirect()->route('facturas-xalapa.index')->with('success', 'Registro actualizado o creado exitosamente.');
    }

    public function storeOaxaca(Request $request)
    {
        // Validar la solicitud
        $validatedData = $request->validate([
            'captura' => 'required|string', // "captura" es obligatorio y de tipo string
        ]);
    
        // Buscar el registro por el campo captura
        $empacado = Factura::where('SERIE', $validatedData['captura'])->first();
        logger('Busqueda: ' .$empacado);
        if ($empacado) {
            // Verificar si se encontró el registro
            logger('Registro encontrado: ' . $empacado->id);
    
            // Si el registro existe, se actualiza
            $empacado->update([
                'CAPTURA' => $validatedData['captura'],
                'ESTATUS' => '1',
            ]);
    
            // Verificar si la actualización se realizó correctamente
            logger('Registro actualizado: ' . $empacado->captura);
        } else {
            // Registrar que no se encontró el registro
            logger('No se encontró el registro con el número de captura: ' . $validatedData['captura']);
            
            return redirect()->route('facturas-xalapa.index')->with('error', 'No existe el registro con el número de captura proporcionado.');
        }
    
        return redirect()->route('facturas-xalapa.index')->with('success', 'Registro actualizado o creado exitosamente.');
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
