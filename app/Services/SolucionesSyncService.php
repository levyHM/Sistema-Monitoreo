<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class SolucionesSyncService
{
    public function sync()
    {
        try {
            ini_set('max_execution_time', 0);
            ini_set('memory_limit', '512M');

            Log::info('🔄 Iniciando sincronización de soluciones...');

            $registros = DB::connection('mysql2')->select("
                SELECT 
                    fdoc_0.DITIPMV,
                    fdoc_0.DNUM,
                    fdoc_0.DPAR1,
                    fdoc_0.DHORA,
                    finv_0.ICOD,
                    fcli_0.CLICOD,
                    fcli_0.CLIDESC10,
                    faxinv_0.AIPRECIO,
                    faxinv_0.AICANT,
                    finv_0.IDESCR
                FROM
                    db152jigafra.falm falm_0,
                    db152jigafra.faxinv faxinv_0,
                    db152jigafra.fcli fcli_0,
                    db152jigafra.fdoc fdoc_0,
                    db152jigafra.finv finv_0
                WHERE
                    fdoc_0.CLISEQ = fcli_0.CLISEQ
                    AND faxinv_0.CLISEQ = fcli_0.CLISEQ
                    AND faxinv_0.DSEQ = fdoc_0.DSEQ
                    AND finv_0.ISEQ = faxinv_0.ISEQ
                    AND falm_0.ISEQ = finv_0.ISEQ
                    AND (
                        fdoc_0.DITIPMV = 'FE'
                        AND fdoc_0.DFECHA >= '2025-08-19'
                        AND falm_0.ALMNUM = '001'
                    )
            ");

            Log::info('📦 Total registros obtenidos: ' . count($registros));

            $insertados = 0;

            foreach ($registros as $solucion) {
                try {
                    $resultado = DB::connection('mysql')->table('catalogo_soluciones_clientes')->insertOrIgnore([
                        'ditipmv'     => $solucion->DITIPMV,
                        'dnum'        => $solucion->DNUM,
                        'dpar1'       => $solucion->DPAR1,
                        'dhora'       => $solucion->DHORA,
                        'icod'        => $solucion->ICOD,
                        'clicod'      => $solucion->CLICOD,
                        'clidesc10'   => $solucion->CLIDESC10,
                        'aiprecio'    => $solucion->AIPRECIO,
                        'aicant'      => $solucion->AICANT,
                        'idescr'      => $solucion->IDESCR,
                        'estatus'     => 1,
                        'created_at'  => now(),
                        'updated_at'  => now()
                    ]);

                    // insertOrIgnore devuelve 1 si se insertó, 0 si se ignoró
                    $insertados += $resultado;
                } catch (Exception $e) {
                    Log::error("❌ Error al insertar ICOD {$solucion->ICOD}: " . $e->getMessage());
                }
            }

            Log::info("✅ Sincronización completada. Insertados: {$insertados}");
        } catch (Exception $e) {
            Log::error('🚨 Error general en la sincronización de soluciones: ' . $e->getMessage());
        }
    }
}
