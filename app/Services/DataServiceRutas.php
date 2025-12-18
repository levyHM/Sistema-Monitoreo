<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DataServiceRutas
{
    public function getFilteredData()
    {
        // Obtener el último AGSEQ ya copiado localmente
        $lastAgseq = DB::connection('mysql')->table('rutas')->max('agseq');

        // Si no hay registros todavía, traer todos
        if (is_null($lastAgseq)) {
            $lastAgseq = 0;
        }

        // Traer solo los registros nuevos
        return DB::connection('mysql2')
            ->table('fag as fag_0')
            ->select('AGSEQ', 'AGTNUM', 'AGDESCR')
            ->where('AGSEQ', '>', $lastAgseq)
            ->orderBy('AGSEQ')
            ->get();
    }

    public function copyNewRecords()
    {
        $newRecords = $this->getFilteredData();

        Log::info('Total de registros nuevos a copiar:', ['total' => $newRecords->count()]);

        foreach ($newRecords as $row) {
            DB::connection('mysql')->table('rutas')->updateOrInsert(
                [
                    'agseq' => $row->AGSEQ,
                ],
                [
                    'agtnum'     => $row->AGTNUM,
                    'agdescr'    => $row->AGDESCR,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
