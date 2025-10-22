<?php

namespace App\Http\Controllers;

use App\Models\CatalogoSolucionesCliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SolucionesController extends Controller
{
    /**
     * Buscar facturas/ICOD del catálogo por cliente.
     */
    public function buscarCatalogo(Request $request)
    {
        $query = $request->get('query', '');
        $clicod = $request->get('clicod', '');

        // Validación básica
        if (!$query || !$clicod) {
            return response()->json([]);
        }

        $consulta = CatalogoSolucionesCliente::query()
            ->where('dnum', 'like', "%{$query}%")
            ->where('clicod', $clicod)
            ->orderBy('dnum')
            ->take(10);

        $resultados = $consulta->get([
            'idCatalogoSolucionesClientes',
            'dnum',
            'icod',
            'idescr',
            'aiprecio',
            'observaciones'
        ]);

        // Log para depuración (opcional, se puede comentar en producción)
        Log::info('Resultados de búsqueda de catálogo', [
            'query' => $query,
            'clicod' => $clicod,
            'total_resultados' => $resultados->count(),
            'sql' => $consulta->toSql(),
            'bindings' => $consulta->getBindings()
        ]);

        return response()->json($resultados);
    }
}
