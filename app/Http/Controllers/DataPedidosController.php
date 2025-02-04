<?php

namespace App\Http\Controllers;

use App\Services\DataServicePedidos;

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
        $data = $this->dataServicePedidos->getFilteredData($lastDateTime);
        return view('welcome', ['data' => $data]);
    }
}
