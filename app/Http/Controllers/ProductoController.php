<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
 
    public function buscar(Request $request)
    {
        $query = $request->get('icod');

        $productos = Producto::where('icod', 'like', "%{$query}%")
            ->take(10)
            ->get(['icod','icodprv', 'idescrip']);

        return response()->json($productos);
    }
}
