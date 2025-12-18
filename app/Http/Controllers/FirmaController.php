<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Firma;
use App\Models\CatalogoFirma;
use App\Models\Recibo;

class FirmaController extends Controller
{
    public function index()
    {
        $firmas = Firma::with(['tipoFirma', 'recibo'])->get();
        return view('firmas.index', compact('firmas'));
    }

    public function create()
    {
        $tiposFirma = CatalogoFirma::all();
        $recibos = Recibo::all();
        return view('firmas.create', compact('tiposFirma', 'recibos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required|string|max:45',
            'catalogo_firma_idcatalogo_firma' => 'required|exists:catalogo_firma,idcatalogo_firma',
            'observaciones' => 'nullable|string|max:45',
            'estatus' => 'nullable|integer',
            'recibos_idrecibos' => 'required|exists:recibos,idrecibos',
        ]);

        Firma::create($request->only([
            'id_user',
            'catalogo_firma_idcatalogo_firma',
            'observaciones',
            'estatus',
            'recibos_idrecibos',
        ]));

        return redirect()->route('firmas.index')->with('success', 'Firma registrada exitosamente.');
    }

    public function edit(Firma $firma)
    {
        $tiposFirma = CatalogoFirma::all();
        $recibos = Recibo::all();
        return view('firmas.edit', compact('firma', 'tiposFirma', 'recibos'));
    }

    public function update(Request $request, Firma $firma)
    {
        $request->validate([
            'id_user' => 'required|string|max:45',
            'catalogo_firma_idcatalogo_firma' => 'required|exists:catalogo_firma,idcatalogo_firma',
            'observaciones' => 'nullable|string|max:45',
            'estatus' => 'nullable|integer',
            'recibos_idrecibos' => 'required|exists:recibos,idrecibos',
        ]);

        $firma->update($request->only([
            'id_user',
            'catalogo_firma_idcatalogo_firma',
            'observaciones',
            'estatus',
            'recibos_idrecibos',
        ]));

        return redirect()->route('firmas.index')->with('success', 'Firma actualizada correctamente.');
    }

    public function destroy(Firma $firma)
    {
        $firma->delete();
        return redirect()->route('firmas.index')->with('success', 'Firma eliminada.');
    }
}
