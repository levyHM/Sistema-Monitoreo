<?php

namespace App\Http\Controllers;

use App\Models\Pedido; // Asegúrate de que el modelo Pedido esté creado
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PedidosController extends Controller
{
    // Mostrar todos los pedidos con paginación
    public function index()
    {
        $pedidos = Pedido::where('SUCURSAL', 'P')  // Puedes agregar otros filtros si es necesario
            ->orderBy('id', 'desc')
            ->paginate(100);
        return view('pages.pedidos-cdmx', compact('pedidos'));
    }

    public function pedidosOaxaca()
    {
        $pedidos = Pedido::where('SUCURSAL', 'PO')  // Puedes agregar otros filtros si es necesario
            ->orderBy('id', 'desc')
            ->paginate(100);
        return view('pages.pedidos-oaxaca', compact('pedidos'));
    }

    public function pedidosXalapa()
    {
        $pedidos = Pedido::where('SUCURSAL', 'PV')  // Puedes agregar otros filtros si es necesario
            ->orderBy('id', 'desc')
            ->paginate(100);
        return view('pages.pedidos-xalapa', compact('pedidos'));
    }

    // Mostrar el formulario para crear un nuevo pedido
    public function create()
    {
        return view('pedidos.create');
    }
    // Guardar un nuevo pedido en la base de datos
    public function store(Request $request)
    {
        // Validar la entrada, asegurando que 'captura' esté presente
        $request->validate([
            'captura' => 'required|string',
        ]);

        // Extraer solo la parte antes del primer '%'
        $capturaValue = explode('%', $request->captura)[0];

        // Buscar el pedido donde el campo SERIE coincida con CAPTURA
        $pedido = Pedido::where('SERIE', $capturaValue)->first();
        Log::info('Pedido encontrado:', ['data' => $pedido]);

        if ($pedido) {
            // Actualizar el campo CAPTURA y cambiar el ESTATUS
            $pedido->update([
                'CAPTURA' => substr($request->captura, 0, 20),
                'ESTATUS' => 1
            ]);

            return $this->redirectBackWithMessage('success', 'Los datos se actualizaron correctamente.');
        } else {
            return $this->redirectBackWithMessage('error', 'No se encontró un registro con el número de captura proporcionado.');
        }
    }
    // Método auxiliar para manejar la redirección dinámica
    private function redirectBackWithMessage($type, $message)
    {
        return redirect()->back()->with($type, $message);
    }

    public function updateCaptura(Request $request)
    {
        // Validar que CAPTURA esté presente en la solicitud
        $request->validate([
            'captura' => 'required|string',
        ]);

        // Extraer solo la parte antes del primer '%'
        $capturaValue = explode('%', $request->captura)[0];

        // Buscar el pedido donde el campo SERIE coincida con CAPTURA
        $pedido = Pedido::where('SERIE', $capturaValue)->first();
        Log::info('Pedido:', ['data' =>  $pedido]);
        if ($pedido) {
            // Actualizar el campo CAPTURA con el valor proporcionado
            $pedido->update([
                'CAPTURA' => $request->captura,
                'ESTATUS' => 1
            ]);

            return redirect()->route('pedidos.index')->with('success', 'Registro actualizado exitosamente.');
        } else {
            return redirect()->route('pedidos.index')->with('error', 'No existe el registro con el número de captura proporcionado.');
        }
    }



    // Mostrar un pedido específico
    public function show($id)
    {
        $pedido = Pedido::findOrFail($id); // Encuentra el pedido por ID
        return view('pedidos.show', compact('pedido'));
    }

    // Mostrar el formulario para editar un pedido
    public function edit($id)
    {
        $pedido = Pedido::findOrFail($id); // Encuentra el pedido por ID
        return view('pedidos.edit', compact('pedido'));
    }

    // Actualizar un pedido en la base de datos
    public function update(Request $request, $id)
    {
        // Validación de datos
        $request->validate([
            'PEFECHA' => 'required|date',
            'PENUM' => 'required|string',
            'PEALMACEN' => 'required|string',
            'PEPAR0' => 'nullable|string',
            'PEPAR1' => 'nullable|string',
            'SUCURSAL' => 'required|string',
        ]);

        $pedido = Pedido::findOrFail($id); // Encuentra el pedido por ID y actualiza
        $pedido->update([
            'PEFECHA' => $request->PEFECHA,
            'PENUM' => $request->PENUM,
            'PEALMACEN' => $request->PEALMACEN,
            'PEPAR0' => $request->PEPAR0,
            'PEPAR1' => $request->PEPAR1,
            'SUCURSAL' => $request->SUCURSAL,
        ]);

        return redirect()->route('pedidos.index')->with('success', 'Pedido actualizado exitosamente.');
    }

    // Eliminar un pedido
    public function destroy($id)
    {
        $pedido = Pedido::findOrFail($id); // Encuentra el pedido por ID
        $pedido->delete(); // Elimina el pedido

        return redirect()->route('pedidos.index')->with('success', 'Pedido eliminado exitosamente.');
    }
}
