<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProveedorSyncService
{
    public function sync()
    {
        // Obtener todos los proveedores desde db152jigafra.fprv
        $query = DB::connection('mysql2')->table('db152jigafra.fprv')
            ->select('PRVCOD', 'PRVNOM');

        // Imprimir el SQL generado ELECT fprv_0.PRVCOD, fprv_0.PRVNOM FROM db152jigafra.fprv fprv_0
        Log::info('SQL generado: ' . $query->toSql());

        $proveedores = $query->get();

        // Iterar e insertar solo los que no existen
        foreach ($proveedores as $prov) {
            $existe = DB::connection('mysql')->table('catalogo_provedores')
                ->where('prvcod', $prov->PRVCOD)
                ->exists();

            if (!$existe) {
                DB::connection('mysql')->table('catalogo_provedores')->insert([
                    'prvcod' => $prov->PRVCOD,
                    'prvnom' => $prov->PRVNOM,
                    'estatus' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        Log::info('Proveedores copiados exitosamente.');
    }
}

