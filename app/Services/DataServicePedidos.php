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
        return DB::connection('mysql2')->select("
            SELECT PESEQ, PEFECHA, PEDATE2, PENUM, PEALMACEN, PEPAR0, PEPAR1
            FROM db152jigafra.fpenc
            WHERE PESEQ > ?
        ", [$lastDateTime]);
    }

    public function copyOrUpdateData()
    {
        // Obtener la última fecha desde la primera base de datos
        $lastDateTime = $this->getLastDateFromFirstDatabase();
        // Filtrar los datos desde la base de datos secundaria
        $data = $this->getFilteredData($lastDateTime);

        // Log para depuración
        Log::info('Filtered Data:', ['data' => $lastDateTime]);

        // Iterar sobre los registros obtenidos y realizar la operación de actualización o inserción
        foreach ($data as $row) {
            // Extraer los primeros dos caracteres de PENUM
            $penumPrefix = substr($row->PENUM, 0, 2);

            // Si los primeros dos caracteres son "PO" o "PV", se mantiene, de lo contrario, se usa "P"
            $penumValue = ($penumPrefix == 'PO' || $penumPrefix == 'PV') ? $penumPrefix : 'P';

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
                    'SERIE' => $row->PENUM . $row->PEPAR1,
                    'ESTATUS' => '0',  // Valor por defecto
                ]
            );
        }
    }
}
