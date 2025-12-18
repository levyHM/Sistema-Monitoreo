<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CatalogoProductoSyncService
{
    public function sync()
    {
        Log::info('===============================================================');
        Log::info('🔄 Iniciando sincronización incremental de catalogo_producto...');

        $ultimaHora = DB::connection('mysql')
            ->table('catalogo_producto')
            ->max('dhora');

        Log::info("🕒 Última hora registrada: {$ultimaHora}");

        // Obtener todos los registros nuevos desde mysql2
        $productos = DB::connection('mysql2')->table('db152jigafra.fdoc as fdoc_0')
            ->join('db152jigafra.fcli as fcli_0', 'fdoc_0.CLISEQ', '=', 'fcli_0.CLISEQ')
            ->join('db152jigafra.faxinv as faxinv_0', function ($join) {
                $join->on('faxinv_0.CLISEQ', '=', 'fcli_0.CLISEQ')
                    ->on('faxinv_0.DSEQ', '=', 'fdoc_0.DSEQ');
            })
            ->join('db152jigafra.finv as finv_0', 'finv_0.ISEQ', '=', 'faxinv_0.ISEQ')
            ->join('db152jigafra.falm as falm_0', 'falm_0.ISEQ', '=', 'finv_0.ISEQ')
            ->select(
                'fdoc_0.DITIPMV',
                'fdoc_0.DNUM',
                'fdoc_0.DPAR1',
                'fdoc_0.DHORA',
                'finv_0.ICOD',
                'fcli_0.CLICOD',
                'fcli_0.CLIDESC10',
                'faxinv_0.AIPRECIO',
                'faxinv_0.AICANT',
                'finv_0.IDESCR'
            )
            ->where('fdoc_0.DITIPMV', 'FE')
            ->where('fdoc_0.DHORA', '>', $ultimaHora)
            ->where('falm_0.ALMNUM', '001')
            ->orderBy('fdoc_0.DHORA')
            ->get();

        $total = $productos->count();
        Log::info("📊 Total registros a sincronizar: {$total}");

        if ($total === 0) {
            Log::info('⚠️ No hay nuevos registros para sincronizar.');
            Log::info('===============================================================');
            return;
        }

        // Dividir en bloques de 200 para evitar el error 1390
        $bloques = array_chunk($productos->toArray(), 200);

        foreach ($bloques as $index => $bloque) {
            foreach ($bloque as $prod) {
                $data = [
                    'ditipmv'      => $prod->DITIPMV,
                    'dnum'         => $prod->DNUM,
                    'dpar1'        => $prod->DPAR1,
                    'dhora'        => $prod->DHORA,
                    'icod'         => $prod->ICOD,
                    'clicod'       => $prod->CLICOD,
                    'clidesc10'    => $prod->CLIDESC10,
                    'aiprecio'     => $prod->AIPRECIO,
                    'aicant'       => $prod->AICANT,
                    'idescr'       => $prod->IDESCR,
                    'updated_at'   => now(),
                    'created_at'   => now(),
                ];

                DB::connection('mysql')->table('catalogo_producto')->updateOrInsert(
                    ['icod' => $prod->ICOD, 'clicod' => $prod->CLICOD],
                    $data
                );
            }

            Log::info("📦 Procesado bloque #" . ($index + 1) . " de " . count($bloque) . " productos.");
        }

        Log::info('✅ Sincronización incremental completada sin errores.');
        Log::info('===============================================================');
    }
}
