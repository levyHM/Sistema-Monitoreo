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
        $pedidos = Pedido::orderBy('ID', 'desc')->paginate(100);
        return view('pages.pedidos', compact('pedidos'));
    }

    // Mostrar el formulario para crear un nuevo pedido
    public function create()
    {
        return view('pedidos.create');
    }

    // Guardar un nuevo pedido en la base de datos
    public function store(Request $request)
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

        // Crear nuevo pedido
        Pedido::create([
            'PEFECHA' => $request->PEFECHA,
            'PENUM' => $request->PENUM,
            'PEALMACEN' => $request->PEALMACEN,
            'PEPAR0' => $request->PEPAR0,
            'PEPAR1' => $request->PEPAR1,
            'SUCURSAL' => $request->SUCURSAL,
        ]);

        return redirect()->route('pedidos.index')->with('success', 'Pedido creado exitosamente.');
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
                'ESTATUS' => 1]);
    
            return redirect()->route('pedidos.index')->with('success', 'CAPTURA actualizada correctamente.');
        } else {
            return redirect()->route('pedidos.index')->with('error', 'No se encontró un pedido con la SERIE proporcionada.');
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
