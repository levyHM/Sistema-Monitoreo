<?php

namespace App\Http\Controllers;

use App\Models\CatalogoCliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CatalogoClienteController extends Controller
{
    public function buscar(Request $request)
    {
        $query = $request->get('q');

        $clientes = CatalogoCliente::where('estatus', 1)
            ->where(function ($q) use ($query) {
                $q->where('clicod', 'like', "%{$query}%")
                    ->orWhere('clinom', 'like', "%{$query}%");
            })
            ->select('idcatalogo_clientes as id', 'clicod', 'clinom as razon_social', 'clipar1','clidesc10')
            ->orderBy('clicod')
            ->limit(10)
            ->get();
        Log::info($query);
        return response()->json($clientes);
    }
}
