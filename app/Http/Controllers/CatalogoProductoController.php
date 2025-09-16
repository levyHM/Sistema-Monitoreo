<?php

namespace App\Http\Controllers;

use App\Models\CatalogoProducto;
use Illuminate\Http\Request;

class CatalogoProductoController extends Controller
{
     public function buscarFactura(Request $request)
    {
        $query = $request->query('query', '');

        $resultados = CatalogoProducto::where('dnum', 'like', "%{$query}%")
                ->orWhere('dpar1', 'like', "%{$query}%")
                ->limit(10)
                ->get();

        return response()->json($resultados);
    }
}
