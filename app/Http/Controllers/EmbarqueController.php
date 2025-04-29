<?php

namespace App\Http\Controllers;

use App\Models\Embarque;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;

class EmbarqueController extends Controller
{
    // Método para mostrar todos los embarques
    public function index()
    {
        $embarques = Embarque::paginate(100); // Paginación de 10 registros por página

        return view('embarque-cdmx', compact('embarques')); // Asegúrate de tener esta vista en resources/views/embarque-cdmx.blade.php
    }

    // Método para mostrar el formulario de creación
    public function create()
    {
        return view('embarques.create'); // Vista para crear embarques
    }

    // Método para guardar un nuevo embarque
    public function store(Request $request)
    {
        // Validar los datos antes de guardarlos
        $request->validate([
            'ID_OPERADOR' => 'required|integer',
            'ID_RUTA' => 'required|integer',
            'ID_CAMIONETA' => 'required|integer',
            'FECHA' => 'required|date',
            'ESCANER' => 'required|unique:embarques,ESCANER|string',
            'FACTURA' => 'required|string',
            'CANTIDAD' => 'required|integer',
            'CLIENTE' => 'required|string',
            'SUCURSAL' => 'required|string',
            'HORA_DE_LLEGADA' => 'required|date_format:H:i:s',
            'HORA_DE_SALIDA' => 'required|date_format:H:i:s',
            'ESTATUS' => 'required|string',
        ]);

        // Crear el embarque
        Embarque::create([
            'ID_OPERADOR' => $request->ID_OPERADOR,
            'ID_RUTA' => $request->ID_RUTA,
            'ID_CAMIONETA' => $request->ID_CAMIONETA,
            'FECHA' => $request->FECHA,
            'ESCANER' => $request->ESCANER,
            'FACTURA' => $request->FACTURA,
            'CANTIDAD' => $request->CANTIDAD,
            'CLIENTE' => $request->CLIENTE,
            'SUCURSAL' => $request->SUCURSAL,
            'HORA_DE_LLEGADA' => $request->HORA_DE_LLEGADA,
            'HORA_DE_SALIDA' => $request->HORA_DE_SALIDA,
            'ESTATUS' => $request->ESTATUS,
        ]);

        return redirect()->route('embarque-cdmx')->with('success', 'Embarque creado correctamente.');
    }

    public function updateEscaner(Request $request)
    {
        $request->validate([
            'ESCANER' => 'required|string',
        ]);
    
        $embarque = Embarque::where('ESCANER', $request->ESCANER)->first();
    
        if ($embarque) {
            if ($embarque->ESTATUS == '1') {
                // Ya está validado
                return redirect()->route('embarque-cdmx')->with('error', 'El embarque ya está validado.');
            }
    
        // Incrementar la validación
        $embarque->VALIDACION = $embarque->VALIDACION + 1;

        // Verificar si VALIDACION es igual a CANTIDAD
        if ($embarque->VALIDACION == $embarque->CANTIDAD) {
            $embarque->ESTATUS = '1';
        }
            $embarque->save();
    
            return redirect()->route('embarque-cdmx')->with('success', 'Embarque actualizado correctamente.');
        }
    
        return redirect()->route('embarque-cdmx')->with('error', 'El ESCANER no existe.');
    }
    
    
    
    public function import(Request $request)
    {
        // Validar que el archivo sea Excel
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);
    
        // Obtener el archivo
        $file = $request->file('file');
    
        // Cargar el archivo Excel utilizando PhpSpreadsheet
        $spreadsheet = IOFactory::load($file);
    
        // Obtener la primera hoja del archivo
        $sheet = $spreadsheet->getActiveSheet();
    
        // Obtener todas las filas de la hoja
        $rows = $sheet->toArray();
    
        // Recorrer todas las filas y guardar los datos en la base de datos
        foreach ($rows as $key => $row) {
            // Registrar en el log el procesamiento de cada fila
            Log::info('Procesando fila: ' . $key, ['data' => $row]);
    
            // Ignorar la primera fila si tiene los encabezados
            if ($key == 0) {
                continue;
            }
    
            // Validar que todos los campos obligatorios tengan un valor
            if (empty($row[0]) || empty($row[1]) || empty($row[2]) || empty($row[4]) || empty($row[5])) {
                Log::warning('Se ignoró una fila debido a campos vacíos.', ['fila' => $key]);
                continue; // Ignorar esta fila y pasar a la siguiente
            }
    
            // Validar si el ESCANER ya existe en la base de datos
            $escanerExistente = Embarque::where('ESCANER', $row[4])->exists();
            if ($escanerExistente) {
                Log::warning('Se ignoró una fila porque el código de escáner ya existe.', ['fila' => $key, 'ESCANER' => $row[4]]);
                continue; // Ignorar esta fila y pasar a la siguiente
            }
    
            // Convertir la fecha solo si es un valor numérico (formato de fecha de Excel)
            $fecha = $row[3]; // Suponiendo que la fecha está en la columna 3
            if (is_numeric($fecha)) {
                $fecha = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($fecha);
            } else {
                $fecha = null; // Si no es válida, dejarla como null
            }
    
            // Crear un nuevo registro de Embarque
            Embarque::create([
                'ID_OPERADOR' => $row[0],
                'ID_RUTA' => $row[1],
                'ID_CAMIONETA' => $row[2],
                'FECHA' => $fecha ?? now()->toDateString(),
                'ESCANER' => $row[4],
                'FACTURA' => substr($row[4], 0, 11),
                'CANTIDAD' => $row[5],
                'VALIDACION' => $row[6],
                'CLIENTE' => substr($row[4], 11),
                'SUCURSAL' => $row[8],
                'HORA_DE_LLEGADA' => $row[9],
                'HORA_DE_SALIDA' => $row[10],
                'OBSERVACIONES' => $row[11],
                'ESTATUS' => $row[12],
            ]);
        }
    
        return back()->with('success', 'Los embarques se importaron correctamente.');
    }
    
    

    
    // Método para mostrar el formulario de edición
    public function edit($id)
    {
        $embarque = Embarque::findOrFail($id);

        return view('embarques.edit', compact('embarque')); // Vista para editar embarques
    }

    // Método para actualizar el embarque
    public function update(Request $request, $id)
    {
        // Validar los datos antes de guardarlos
        $request->validate([
            'ID_OPERADOR' => 'required|integer',
            'ID_RUTA' => 'required|integer',
            'ID_CAMIONETA' => 'required|integer',
            'FECHA' => 'required|date',
            'ESCANER' => 'required|string|unique:embarques,ESCANER,' . $id,
            'CANTIDAD' => 'required|integer',
            'CLIENTE' => 'required|string',
            'SUCURSAL' => 'required|string',
            'HORA_DE_LLEGADA' => 'required|date_format:H:i:s',
            'HORA_DE_SALIDA' => 'required|date_format:H:i:s',
            'ESTATUS' => 'required|string',
        ]);

        $embarque = Embarque::findOrFail($id);

        // Actualizar el embarque
        $embarque->update([
            'ID_OPERADOR' => $request->ID_OPERADOR,
            'ID_RUTA' => $request->ID_RUTA,
            'ID_CAMIONETA' => $request->ID_CAMIONETA,
            'FECHA' => $request->FECHA,
            'ESCANER' => $request->ESCANER,
            'CANTIDAD' => $request->CANTIDAD,
            'CLIENTE' => $request->CLIENTE,
            'SUCURSAL' => $request->SUCURSAL,
            'HORA_DE_LLEGADA' => $request->HORA_DE_LLEGADA,
            'HORA_DE_SALIDA' => $request->HORA_DE_SALIDA,
            'ESTATUS' => $request->ESTATUS,
        ]);

        return redirect()->route('embarque-cdmx')->with('success', 'Embarque actualizado correctamente.');
    }

    // Método para eliminar un embarque
    public function destroy($id)
    {
        $embarque = Embarque::findOrFail($id);
        $embarque->delete();

        return redirect()->route('embarques.index')->with('success', 'Embarque eliminado correctamente.');
    }
}
