<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CatalogoSolucionesCliente;

class SolucionesController extends Controller
{
    public function buscarCatalogo(Request $request)
    {
        $icod = $request->get('icod', '');
        if (!$icod) return response()->json([]);

        $resultados = CatalogoSolucionesCliente::where('icod', 'like', $icod . '%')
            ->orderBy('icod')
            ->take(10)
            ->get(['idCatalogoSolucionesClientes', 'icod', 'idescr', 'aiprecio', 'observaciones']);

        return response()->json($resultados);
    }
}
