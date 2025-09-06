<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CatalogoProveedor;

class CatalogoProveedorController extends Controller
{
    public function index()
    {
        $proveedores = CatalogoProveedor::all();
        return view('proveedores.index', compact('proveedores'));
    }

    public function create()
    {
        return view('proveedores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'prvcod' => 'required|unique:catalogo_provedores',
            'prvnom' => 'required',
            'estatus' => 'nullable|integer',
        ]);

        CatalogoProveedor::create($request->only('prvcod', 'prvnom', 'estatus'));

        return redirect()->route('proveedores.index')->with('success', 'Proveedor registrado exitosamente.');
    }

    public function edit(CatalogoProveedor $proveedor)
    {
        return view('proveedores.edit', compact('proveedor'));
    }

    public function update(Request $request, CatalogoProveedor $proveedor)
    {
        $request->validate([
            'prvcod' => 'required|unique:catalogo_provedores,prvcod,' . $proveedor->idcatalogo_provedores,
            'prvnom' => 'required',
            'estatus' => 'nullable|integer',
        ]);

        $proveedor->update($request->only('prvcod', 'prvnom', 'estatus'));

        return redirect()->route('proveedores.index')->with('success', 'Proveedor actualizado correctamente.');
    }

    public function destroy(CatalogoProveedor $proveedor)
    {
        $proveedor->delete();
        return redirect()->route('proveedores.index')->with('success', 'Proveedor eliminado.');
    }
}
