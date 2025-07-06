<?php

namespace App\Http\Controllers;

use App\Models\Ruta;
use Illuminate\Http\Request;

class RutaController extends Controller
{
    // Mostrar todas las rutas
    public function index()
    {
        $rutas = Ruta::all();
        return view('rutas.index', compact('rutas'));
    }

    // Mostrar el formulario para crear una nueva ruta
    public function create()
    {
        return view('rutas.create');
    }

    // Almacenar una nueva ruta en la base de datos
    public function store(Request $request)
    {
        $request->validate([
            'AGSEQ' => 'required|string|max:255',
            'AGTNUM' => 'required|string|max:255',
            'AGDESCR' => 'required|string|max:255',
        ]);

        Ruta::create([
            'AGSEQ' => $request->AGSEQ,
            'AGTNUM' => $request->AGTNUM,
            'AGDESCR' => $request->AGDESCR,
        ]);

        return redirect()->route('rutas.index')->with('success', 'Ruta creada exitosamente.');
    }

    // Mostrar el formulario para editar una ruta existente
    public function edit(Ruta $ruta)
    {
        return view('rutas.edit', compact('ruta'));
    }

    // Actualizar una ruta en la base de datos
    public function update(Request $request, Ruta $ruta)
    {
        $request->validate([
            'AGSEQ' => 'required|string|max:255',
            'AGTNUM' => 'required|string|max:255',
            'AGDESCR' => 'required|string|max:255',
        ]);

        $ruta->update([
            'AGSEQ' => $request->AGSEQ,
            'AGTNUM' => $request->AGTNUM,
            'AGDESCR' => $request->AGDESCR,
        ]);

        return redirect()->route('rutas.index')->with('success', 'Ruta actualizada exitosamente.');
    }

    // Eliminar una ruta de la base de datos
    public function destroy(Ruta $ruta)
    {
        $ruta->delete();

        return redirect()->route('rutas.index')->with('success', 'Ruta eliminada exitosamente.');
    }
}
