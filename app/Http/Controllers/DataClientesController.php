<?php

namespace App\Http\Controllers;

use App\Services\DataServiceClientes;

class DataClientesController extends Controller
{
    protected $dataServiceClientes;

    public function __construct(DataServiceClientes $dataServiceClientes)
    {
        $this->dataServiceClientes = $dataServiceClientes;
    }

    public function copyData()
    {
        $this->dataServiceClientes->copyOrUpdateData();
        
        return response()->json('Datos copiados y actualizados exitosamente!', 200);
    }

    public function index()
    {
        $data = $this->dataServiceClientes->getFilteredData();
        return view('welcome', ['data' => $data]);
    }
}