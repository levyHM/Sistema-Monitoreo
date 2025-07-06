<?php

namespace App\Http\Controllers;

use App\Services\DataServicePedidos;
use Illuminate\Support\Facades\Log;

class DataPedidosController extends Controller
{
    protected $dataServicePedidos;

    public function __construct(DataServicePedidos $dataServicePedidos) // Cambié el nombre aquí
    {
        $this->dataServicePedidos = $dataServicePedidos;
    }

    public function copyData()
    {
        $this->dataServicePedidos->copyOrUpdateData();
        
        return response()->json('Datos copiados y actualizados exitosamente!', 200);
    }

    public function index()
    {
        $lastDateTime = $this->dataServicePedidos->getLastDateFromFirstDatabase();
        Log::info('Última fecha obtenida de la primera base de datos:', ['lastDateTime' => $lastDateTime]);
        $data = $this->dataServicePedidos->getFilteredData($lastDateTime);
        return view('welcome', ['data' => $data]);
    }
}
