<?php

namespace App\Http\Controllers;

use App\Models\CatalogoFaltante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CatalogoFaltanteController extends Controller
{
    public function buscar(Request $request)
    {
        $query = $request->get('query', '');

        // Traer todos los campos de la tabla
        $clientes = CatalogoFaltante::where('codigo', 'like', "%{$query}%")
            ->orderBy('codigo', 'ASC')
            ->limit(10) // opcional
            ->get();

        // Imprimir en logs
        Log::info($clientes);

        // Devolver JSON con todos los campos
        return response()->json($clientes);
    }
}
