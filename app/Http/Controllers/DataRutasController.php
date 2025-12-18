<?php

namespace App\Http\Controllers;

use App\Services\DataServiceRutas;

class DataRutasController extends Controller
{
    protected $dataServiceRutas;

    public function __construct(DataServiceRutas $dataServiceRutas)
    {
        $this->dataServiceRutas = $dataServiceRutas;
    }

    public function copyData()
    {
        $this->dataServiceRutas->copyNewRecords();
        return response()->json('Rutas copiadas y actualizadas exitosamente!', 200);
    }

}
