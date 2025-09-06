<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductoSyncService
{
    public function sync()
    {
        $query = DB::connection('mysql2')->table('db152jigafra.finv')
            ->select('ICOD', 'IDESCR', 'ICODPRV');

        Log::info('SQL generado: ' . $query->toSql());

        $productos = $query->get();

        foreach ($productos as $prod) {
            $data = [
                'idescrip' => $prod->IDESCR,
                'estatus' => 1,
                'updated_at' => now(),
                'created_at' => now(), // Solo se usa si se inserta
            ];

            // Si ICOD inicia con "JR" y ICODPRV no es null, se agrega
            if (str_starts_with($prod->ICOD, 'JR') && !is_null($prod->ICODPRV)) {
                $data['icodprv'] = $prod->ICODPRV;
            }

            DB::connection('mysql')->table('productos')->updateOrInsert(
                ['icod' => $prod->ICOD],
                $data
            );
        }

        Log::info('Productos sincronizados exitosamente con condición JR.');
    }
}
