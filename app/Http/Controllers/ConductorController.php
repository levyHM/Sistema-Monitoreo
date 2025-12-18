<?php

namespace App\Http\Controllers;

use App\Models\Conductor;
use Illuminate\Http\Request;

class ConductorController extends Controller
{
    public function index()
    {
        $conductores = Conductor::all(); // O con algún filtro según tus necesidades
        return view('conductores.index', compact('conductores'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'clave' => 'required|unique:conductores,clave',
            'operador' => 'required|string|max:255',
            'observaciones' => 'nullable|string',
        ]);

        return Conductor::create($validated);
    }

    public function show($id)
    {
        return Conductor::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $conductor = Conductor::findOrFail($id);

        $validated = $request->validate([
            'clave' => 'required|unique:conductores,clave,' . $conductor->id,
            'operador' => 'required|string|max:255',
            'observaciones' => 'nullable|string',
        ]);

        $conductor->update($validated);

        return $conductor;
    }

    public function destroy($id)
    {
        Conductor::destroy($id);
        return response()->json(['mensaje' => 'Conductor eliminado']);
    }
}
