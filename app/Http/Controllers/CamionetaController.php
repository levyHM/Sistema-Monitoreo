<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Camioneta;


class CamionetaController extends Controller
{
    public function index()
    {
        $camionetas = Camioneta::all();
        return view('camionetas.index', compact('camionetas'));
    }

    public function create()
    {
        return view('camionetas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'clave' => 'required|unique:camionetas',
            'placas' => 'required|unique:camionetas',
            'marca' => 'required',
        ]);

        Camioneta::create($request->only('clave', 'placas', 'marca'));

        return redirect()->route('camionetas.index')->with('success', 'Camioneta registrada exitosamente.');
    }

    public function edit(Camioneta $camioneta)
    {
        return view('camionetas.edit', compact('camioneta'));
    }

    public function update(Request $request, Camioneta $camioneta)
    {
        $request->validate([
            'clave' => 'required|unique:camionetas,clave,' . $camioneta->id,
            'placas' => 'required|unique:camionetas,placas,' . $camioneta->id,
            'marca' => 'required',
        ]);

        $camioneta->update($request->only('clave', 'placas', 'marca'));

        return redirect()->route('camionetas.index')->with('success', 'Camioneta actualizada correctamente.');
    }

    public function destroy(Camioneta $camioneta)
    {
        $camioneta->delete();
        return redirect()->route('camionetas.index')->with('success', 'Camioneta eliminada.');
    }
}
