<?php

namespace App\Http\Controllers;

use App\Models\CatalogoProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CatalogoProductoController extends Controller
{
    public function buscarFactura(Request $request)
    {
        $query = $request->query('query', '');
        $clicod = $request->query('clicod', '');

        $resultados = CatalogoProducto::where('dnum', 'like', "%{$query}%")
            ->where('clicod', $clicod) 
            ->limit(10);

        // Ejecutar la consulta
        $resultados = $resultados->get();

        Log::info('Consulta buscarFactura', [
            'query' => $query,
            'clicod' => $clicod,
            'resultados' => $resultados->toArray()
        ]);
        return response()->json($resultados);
    }
}
