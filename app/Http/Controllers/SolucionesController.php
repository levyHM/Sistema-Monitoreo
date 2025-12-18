<?php

namespace App\Http\Controllers;

use App\Models\CatalogoSolucionesCliente;
use Illuminate\Http\Request;

class SolucionesController extends Controller
{
    /**
     * Buscar facturas/ICOD del catálogo por cliente.
     */
    public function buscarCatalogo(Request $request)
    {
        $query = $request->get('query', '');
        $clicod = $request->get('clicod', '');

        if (!$query || !$clicod) {
            return response()->json(['data' => [], 'links' => '']);
        }

        $facturas = CatalogoSolucionesCliente::where('clicod', $clicod)
            ->where(function ($q) use ($query) {
                $q->where('dnum', 'like', "%{$query}%")
                    ->orWhere('icod', 'like', "%{$query}%");
            })
            ->paginate(50);

        return response()->json([
            'data' => $facturas->items(),
            'links' => (string) $facturas->links('pagination::bootstrap-4'),
        ]);
    }
}
