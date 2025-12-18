<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    // Obtener todos los clientes
    public function index()
    {
        $clientes = Cliente::all();
        return response()->json($clientes);
    }

    // Buscar cliente
    public function buscarClientes(Request $request)
    {
        $query = $request->get('q'); // Obtén el término de búsqueda
        $clientes = Cliente::where('CLICOD', 'LIKE', '%' . $query . '%') // Filtra por nombre
            ->limit(10) // Limita la cantidad de resultados
            ->get();

        return response()->json($clientes); // Devuelve los resultados en JSON
    }

    public function obtenerCliente(Request $request)
    {
        $codigo = $request->get('codigo'); // Código capturado en el campo Escaner

        // Si el código no tiene la longitud esperada
        if (strlen($codigo) <= 11) {
            return response()->json(['error' => 'El código debe tener más de 11 caracteres'], 400);
            
        }

        // Buscar al cliente según el código
        $cliente = Cliente::where('CLICOD', substr($codigo, 11))->first();

        if (!$cliente) {
            return response()->json(['error' => 'Cliente no encontrado'], 404);
        }

        return response()->json($cliente);
    }


    // Crear un nuevo cliente
    public function store(Request $request)
    {
        $request->validate([
            'CLICOD' => 'required|string|max:10|unique:clientes',
            'CLINOM' => 'required|string|max:100',
            'CLICD' => 'required|string|max:50',
            'CLIPAR1' => 'required|string|max:10',
            'CLIPAR7' => 'required|string|max:10',
            'CLISUCURSAL' => 'required|string|max:50',
            'CLINOM2' => 'required|string|max:100',
        ]);

        $cliente = Cliente::create($request->all());
        return response()->json($cliente, 201);
    }

    // Obtener un cliente por ID
    public function show($id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        return response()->json($cliente);
    }

    // Actualizar un cliente existente
    public function update(Request $request, $id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        $request->validate([
            'CLICOD' => 'required|string|max:10|unique:clientes,CLICOD,' . $id,
            'CLINOM' => 'required|string|max:100',
            'CLICD' => 'required|string|max:50',
            'CLIPAR1' => 'required|string|max:10',
            'CLIPAR7' => 'required|string|max:10',
            'CLISUCURSAL' => 'required|string|max:50',
            'CLINOM2' => 'required|string|max:100',
        ]);

        $cliente->update($request->all());
        return response()->json($cliente);
    }

    // Eliminar un cliente
    public function destroy($id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        $cliente->delete();
        return response()->json(['message' => 'Cliente eliminado'], 200);
    }
}
