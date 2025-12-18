<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DataServiceClientes
{

    public function getFilteredData()
    {

        return DB::connection('mysql2')->select("SELECT fcli_0.CLICOD, fcli_0.CLINOM, fcli_0.CLICD, fcli_0.CLIPAR1, fcli_0.CLIPAR7, fcli_0.CLISUCURSAL, fcli_0.CLINOM2
FROM db152jigafra.fcli fcli_0
WHERE (fcli_0.CLIPAR1<>'1Z99')");
        
    }

    public function copyOrUpdateData()
    {
        $data = $this->getFilteredData();
        Log::info('Total de registros a copiar:', ['total' => count($data)]);
        foreach ($data as $row) {
            DB::connection('mysql')->table('clientes')->updateOrInsert(
                [
                    'CLICOD' => $row->CLICOD, // Clave primaria
                ],
                [
                    'CLINOM'      => $row->CLINOM,
                    'CLICD'       => $row->CLICD,
                    'CLIPAR1'     => $row->CLIPAR1,
                    'CLIPAR7'     => $row->CLIPAR7,
                    'CLISUCURSAL' => $row->CLISUCURSAL,
                    'CLINOM2'     => $row->CLINOM2,
                    'updated_at'  => now(),
                    'created_at'  => now(),
                ]
            );
        }
    }

}
