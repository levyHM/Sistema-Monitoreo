<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CatalogoFirma;

class CatalogoFirmaController extends Controller
{
    public function index()
    {
        $tiposFirma = CatalogoFirma::all();
        return view('catalogo_firma.index', compact('tiposFirma'));
    }

    public function create()
    {
        return view('catalogo_firma.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string|max:45',
            'observaciones' => 'nullable|string|max:45',
            'estatus' => 'nullable|integer',
        ]);

        CatalogoFirma::create($request->only('descripcion', 'observaciones', 'estatus'));

        return redirect()->route('catalogo_firma.index')->with('success', 'Tipo de firma registrado exitosamente.');
    }

    public function edit(CatalogoFirma $catalogoFirma)
    {
        return view('catalogo_firma.edit', compact('catalogoFirma'));
    }

    public function update(Request $request, CatalogoFirma $catalogoFirma)
    {
        $request->validate([
            'descripcion' => 'required|string|max:45',
            'observaciones' => 'nullable|string|max:45',
            'estatus' => 'nullable|integer',
        ]);

        $catalogoFirma->update($request->only('descripcion', 'observaciones', 'estatus'));

        return redirect()->route('catalogo_firma.index')->with('success', 'Tipo de firma actualizado correctamente.');
    }

    public function destroy(CatalogoFirma $catalogoFirma)
    {
        $catalogoFirma->delete();
        return redirect()->route('catalogo_firma.index')->with('success', 'Tipo de firma eliminado.');
    }
}
