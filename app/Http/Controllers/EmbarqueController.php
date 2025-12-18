<?php

namespace App\Http\Controllers;

use App\Models\Embarque;
use App\Models\FacturaParte;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;
use App\Models\Conductor;
use App\Models\Ruta;
use App\Models\Camioneta;
use App\Models\Cliente;

class EmbarqueController extends Controller
{
    // Método para mostrar todos los embarques
    public function index(Request $request)
    {
        $conductores = Conductor::all(); // Obtener todos los conductores
        $rutas = Ruta::all();
        $camionetas = Camioneta::all();

        // Obtener el ID del conductor seleccionado
        $idConductor = $request->input('ID_OPERADOR');

        // Filtrar embarques si hay conductor seleccionado, sino mostrar todos
        $embarques = Embarque::with(['facturaPartes'])
            ->when($idConductor, function ($query) use ($idConductor) {
                return $query->where('id_conductor', $idConductor);
            })
            ->paginate(100);

        return view('embarque-cdmx', compact('embarques', 'conductores', 'rutas', 'camionetas'));
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
            'id_conductor' => $request->ID_OPERADOR,
            'ID_RUTA' => $request->ID_RUTA,
            'ID_CAMIONETA' => $request->ID_CAMIONETA,
            'FECHA' => $request->FECHA,
            'ESCANER' => $request->ESCANER,
            'FACTURA' => $request->FACTURA,
            'CANTIDAD' => $request->CANTIDAD,
            'CLIENTE' => $request->CLIENTE,
            'url_img' => $request->SUCURSAL,
            'HORA_DE_LLEGADA' => $request->HORA_DE_LLEGADA,
            'HORA_DE_SALIDA' => $request->HORA_DE_SALIDA,
            'ESTATUS' => $request->ESTATUS,
        ]);

        return redirect()->route('embarque-cdmx')->with('success', 'Embarque creado correctamente.');
    }

    public function updateEscaner(Request $request)
    {
        // Validación de datos
        $request->validate([
            'factura' => 'required|string', // Asegurar que factura está presente
        ]);
        $factura = substr($request->factura, 0, 11);
        $parte_num = substr($request->factura, -3, 1);
        $escaner = substr($request->factura, 0, -3);
        // Buscar el embarque basado en ESCANER
        $embarque = Embarque::where('ESCANER', $escaner)->first();
        if (!$embarque) {
            return redirect()->route('embarque-cdmx')->with('error', 'El ESCANER no existe.');
        }

        if ($embarque->ESTATUS == '1') {
            return redirect()->route('embarque-cdmx')->with('error', 'El embarque ya está validado.');
        }
        // Registrar en el log los valores de factura y parte_num
        Log::info('Valores procesados:', ['factura' => $factura, 'parte_num' => $parte_num]);
        // Buscar la factura
        $facturaParte = FacturaParte::where('factura', $factura)
            ->where('parte_num', $parte_num)
            ->first();

        if (!$facturaParte) {
            return redirect()->route('embarque-cdmx')->with('error', 'No se encontró la factura.');
        }

        // Verificar si ya está validado
        if (intval($facturaParte->estado_validacion) === 1) {
            return redirect()->route('embarque-cdmx')->with('error', 'El embarque ya está validado.');
        }

        // Actualizar estado de validación
        $facturaParte->estado_validacion = 1;
        $facturaParte->save();
        // Incrementar la validación
        $embarque->VALIDACION += 1;
        if ($embarque->VALIDACION == $embarque->CANTIDAD) {
            $embarque->ESTATUS = '1';
        }
        // Guardar cambios en el embarque
        $embarque->save();
        return redirect()->route('embarque-cdmx')->with('success', 'Embarque actualizado correctamente.');
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
            $embarque = Embarque::create([
                'id_conductor' => $row[0],
                'ID_RUTA' => $row[1],
                'ID_CAMIONETA' => $row[2],
                'FECHA' => $fecha ?? now()->toDateString(),
                'ESCANER' => $row[4],
                'FACTURA' => substr($row[4], 0, 11),
                'CANTIDAD' => $row[5],
                'VALIDACION' => $row[6],
                'id_cliente' => Cliente::where('CLICOD', substr($row[4], 11))->value('id'),
                'url_img' => "pendiente", // Asignar un valor por defecto o personalizado
                'HORA_DE_LLEGADA' => $row[7],
                'HORA_DE_SALIDA' => $row[8],
                'OBSERVACIONES' => $row[9],
                'ESTATUS' => $row[10],
            ]);

            // Verificar que el objeto $embarque tiene un ID
            Log::info('Embarque ID: ' . $embarque->id);

            // Crear registros en factura_partes
            $parte_num = 1; // Aquí asumimos que parte_num es un contador. Cambia según tu lógica.
            $total_partes = $row[5]; // Este es un ejemplo, deberías definir cómo calcular o asignar el total de partes.

            for ($i = 1; $i <= $total_partes; $i++) {
                // Guardar las partes de la factura asociadas al embarque
                FacturaParte::create([
                    'embarques_id' => $embarque->id, // Usamos la variable correcta
                    'factura' => substr($row[4], 0, 11), // Usando el mismo valor de factura
                    'parte_num' => $parte_num++, // Incrementar el número de la parte
                    'estado_validacion' => 0, // Inicialmente, no validado
                    'observaciones' => 'Parte de factura importada', // Opcional: puedes personalizar esto
                ]);
            }
        }

        return back()->with('success', 'Los embarques se importaron correctamente.');
    }



    public function edit(Request $request, $id)
    {
        $conductores = Conductor::all(); // Obtener todos los conductores disponibles
        $idConductor = $request->input('ID_OPERADOR'); // ID del conductor seleccionado

        // Filtrar y paginar los embarques correctamente
        $embarques = Embarque::with(['facturaPartes'])
            ->when($idConductor, function ($query) use ($idConductor) {
                return $query->where('id_conductor', $idConductor);
            })
            ->paginate(100);

        // Obtener el primer embarque de la paginación
        $embarque = $embarques->first();

        return view('embarques.edit', compact('conductores', 'embarques', 'embarque'));
    }

    // Método para actualizar el embarque
    public function update(Request $request, $id)
    {
        // Validar los datos antes de guardarlos
        $request->validate([
            'observaciones' => 'required|string',
            'archivoImagen' => 'nullable|image|max:2048',
        ]);

        $embarque = Embarque::findOrFail($id);

        // Actualizar el embarque
        $embarque->update([
            'OBSERVACIONES' => $request->observaciones,
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
