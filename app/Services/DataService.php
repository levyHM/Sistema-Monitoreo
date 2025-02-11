<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DataService
{
    public function getLastDateFromFirstDatabase()
    {
        // Obtener la última fecha y hora de la primera base de datos
        $lastRecord = DB::connection('mysql')->table('facturas')
            ->orderBy('DFECHA', 'desc')
            ->orderBy('DHORA', 'desc')
            ->first();

        return $lastRecord ? $lastRecord->DFECHA . ' ' . $lastRecord->DHORA : '2025-02-01 00:00:00';
    }

    public function getFilteredData($lastDateTime)
    {

        return DB::connection('mysql2')->select("
            SELECT fdoc_0.DITIPMV, fdoc_0.DNUM, fdoc_0.DFECHA, fcli_0.CLICOD, fdoc_0.DPAR1, fdoc_0.DHORA 
            FROM db152jigafra.fcli fcli_0, db152jigafra.fdoc fdoc_0 
            WHERE fdoc_0.CLISEQ = fcli_0.CLISEQ 
            AND fdoc_0.DITIPMV in ('FE','FO','FV')
            AND fdoc_0.DHORA > ?
        ", [$lastDateTime]);
        
    }

    public function copyOrUpdateData()
    {
        $lastDateTime = $this->getLastDateFromFirstDatabase();
        $data = $this->getFilteredData($lastDateTime);
        Log::info('Filtered Data:', ['data' => $lastDateTime]);
        
        foreach ($data as $row) {
            DB::connection('mysql')->table('facturas')->updateOrInsert(
                [
                    'DITIPMV' => $row->DITIPMV,
                    'DNUM' => $row->DNUM,
                ],
                [
                    'DFECHA' => $row->DFECHA,
                    'CLICOD' => $row->CLICOD,
                    'DPAR1' => $row->DPAR1,
                    'DHORA' => $row->DHORA,
                    'SERIE' => $row->DNUM.$row->CLICOD,
                    'ESTATUS' => '0',
                ]
            );
        }
    }

}
