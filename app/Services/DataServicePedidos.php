<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DataServicePedidos
{
    public function getLastDateFromFirstDatabase()
    {
        // Obtener el último PESEQ de la base de datos, ordenando por PESEQ
        $lastRecord = DB::connection('mysql')->table('pedidos')
            ->orderBy('PESEQ', 'desc') // Ordenar por PESEQ en orden descendente
            ->first();

        Log::info('Último registro obtenido de la base de datos:', ['lastRecord' => $lastRecord]);

        // Si no hay registros, devolver 0
        return $lastRecord ? $lastRecord->PESEQ : '0';
    }

    public function getFilteredData($lastDateTime)
    {
        return DB::connection('mysql2')->select("SELECT DISTINCT fpenc_0.PESEQ, fpenc_0.PEFECHA, fpenc_0.PEDATE2, fpenc_0.PENUM, fpenc_0.PENUMELLOS,fpenc_0.PEALMACEN, fpenc_0.PEPAR0, fpenc_0.PEPAR1
FROM db152jigafra.fpenc fpenc_0
WHERE (fpenc_0.PEPAR1 NOT IN ('', '1') and fpenc_0.PESEQ>?)", [$lastDateTime]);
    }

    public function copyOrUpdateData()
    {
        // Obtener la última fecha desde la primera base de datos
        $lastDateTime = $this->getLastDateFromFirstDatabase();

        // Filtrar los datos desde la base de datos secundaria
        Log::info('Última fecha obtenida de la primera base de datos:', ['lastDateTime' => $lastDateTime]);
        $data = $this->getFilteredData($lastDateTime);

        // Lista de valores específicos de PEPAR1 para los cuales ESTATUS debe ser 3
        $specificValues = [
            '1Z33',
            '1Z31',
            '1Z29',
            '1Z28',
            '1Z27',
            '1Z25',
            '1Z23',
            '1Z09',
            '1Z38',
            '1Z32',
            '1Z44',
            '1Z41',
            '1Z21',
            '1Z35',
            '1O02',
            '1O06',
            '1O08',
            '1O09',
            '1O10',
            '1O12',
            '1O13'
        ];
        foreach ($data as $row) {
            $penumPrefix = substr($row->PENUM, 0, 2);
            $penumValue = ($penumPrefix == 'PO' || $penumPrefix == 'PV') ? $penumPrefix : 'P';

        // Determinar ESTATUS con prioridad: TV -> 5, SUGERIDO -> 6, luego lista específica -> 3, y por último 0
        Log::info('DATA', ['DATA' => $row]);
            if ($row->PENUMELLOS == 'SUGERIDO') {
                $estatusValue = 5;
                Log::info('Registro SUGERIDO:', ['SUGERIDO' => $row->PENUMELLOS]);
            } elseif (in_array($row->PEPAR1, $specificValues)) {
                $estatusValue = 3;
                 Log::info('Registro 3:', ['3' => $row->PEALMACEN]);
            } else {
                $estatusValue = 0;
                 Log::info('Registro 0:', ['0' => $row->PEALMACEN]);
            }

            $newRecord = [
                'PESEQ'    => $row->PESEQ,
                'PEFECHA'  => $row->PEFECHA,
                'PEDATE2'  => $row->PEDATE2,
                'PENUM'    => $row->PENUM,
                'PEALMACEN' => $row->PEALMACEN,
                'PEPAR0'   => $row->PEPAR0,
                'PEPAR1'   => $row->PEPAR1,
                'SUCURSAL' => $penumValue,
                'SERIE'    => $row->PENUM . substr($row->PEPAR1, 1),
                'ESTATUS'  => $estatusValue,
                'created_at' => now(),
                'updated_at' => now()
            ];

            // Buscar si ya existe el registro con PESEQ y PENUM
            $existing = DB::connection('mysql')->table('pedidos')
                ->where('PENUM', $row->PENUM)
                ->first();
            if (!$existing) {
                // Insertar solo si no existe
                DB::connection('mysql')->table('pedidos')->insert($newRecord);
               // Log::info('Registro insertado:', ['Insertado' => $row]);
            } else {
                // Comparar los campos clave antes de actualizar
               // Log::info('Registro ya existente sin cambios:', ['existente' => $row]);
            }
        }
    }
}
