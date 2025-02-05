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

        // Si no hay registros, devolver 0
        return $lastRecord ? $lastRecord->PESEQ : '0';
    }

    public function getFilteredData($lastDateTime)
    {
        return DB::connection('mysql2')->select("SELECT DISTINCT fpenc_0.PESEQ, fpenc_0.PEFECHA, fpenc_0.PEDATE2, fpenc_0.PENUM, fpenc_0.PEALMACEN, fpenc_0.PEPAR0, fpenc_0.PEPAR1
FROM db152jigafra.fpenc fpenc_0
WHERE (PEALMACEN='001' and fpenc_0.PEPAR1 NOT IN ('', '1') and fpenc_0.PESEQ>?)", [$lastDateTime]);
    }

    public function copyOrUpdateData()
    {
        // Obtener la última fecha desde la primera base de datos
        $lastDateTime = $this->getLastDateFromFirstDatabase();
        // Filtrar los datos desde la base de datos secundaria
        $data = $this->getFilteredData($lastDateTime);
    
        // Log para depuración
        Log::info('Filtered Data:', ['data' => $lastDateTime]);
    
        // Lista de valores específicos de PEPAR1 para los cuales ESTATUS debe ser 3
        $specificValues = [
            '1Z33', '1Z31', '1Z29', '1Z28', '1Z27', '1Z25', '1Z23', '1Z09',
            '1Z38', '1Z32', '1Z44', '1Z41', '1Z21', '1Z35'
        ];
    
        // Iterar sobre los registros obtenidos y realizar la operación de actualización o inserción
        foreach ($data as $row) {
            // Extraer los primeros dos caracteres de PENUM
            $penumPrefix = substr($row->PENUM, 0, 2);
    
            // Si los primeros dos caracteres son "PO" o "PV", se mantiene, de lo contrario, se usa "P"
            $penumValue = ($penumPrefix == 'PO' || $penumPrefix == 'PV') ? $penumPrefix : 'P';
    
            // Determinar el valor de ESTATUS
            $estatusValue = in_array($row->PEPAR1, $specificValues) ? '3' : '0';
    
            DB::connection('mysql')->table('pedidos')->updateOrInsert(
                [
                    // Este es el conjunto de condiciones de búsqueda
                    'PESEQ' => $row->PESEQ,  // Identificador único para la búsqueda
                ],
                [
                    // Estos son los campos que quieres actualizar o insertar
                    'PEFECHA' => $row->PEFECHA,
                    'PEDATE2' => $row->PEDATE2,
                    'PENUM' => $row->PENUM,
                    'PEALMACEN' => $row->PEALMACEN,
                    'PEPAR0' => $row->PEPAR0,
                    'PEPAR1' => $row->PEPAR1,
                    'SUCURSAL' => $penumValue,
                    'SERIE' => $row->PENUM . substr($row->PEPAR1, 1),
                    'ESTATUS' => $estatusValue,  // Valor ajustado según PEPAR1
                ]
            );
        }
    }
    
}
