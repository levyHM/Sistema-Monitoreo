<?php

namespace App\Http\Controllers;

use App\Services\DataService;

class DataController extends Controller
{
    protected $dataService;

    public function __construct(DataService $dataService)
    {
        $this->dataService = $dataService;
    }

    public function copyData()
    {
        $this->dataService->copyOrUpdateData();
        
        return response()->json('Datos copiados y actualizados exitosamente!', 200);
    }

    public function index()
    {
        $lastDateTime = $this->dataService->getLastDateFromFirstDatabase();
        $data = $this->dataService->getFilteredData($lastDateTime);
        return view('welcome', ['data' => $data]);
    }
}