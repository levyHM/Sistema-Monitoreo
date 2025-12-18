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
            Log::info('===============================================================');
            Log::info('🔄 Iniciando sincronización Soluciónes a clientes');

            // 1. Obtener el último dhora insertado
            $ultimaHora = DB::connection('mysql')
                ->table('catalogo_soluciones_clientes')
                ->max('dhora');

            Log::info("🕒 Última hora registrada: {$ultimaHora}");

            // 2. Consulta con filtro incremental
            $registros = DB::connection('mysql2')->select("
            SELECT 
                fdoc_0.DSEQ,
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
                AND fdoc_0.DITIPMV = 'FE'
                AND falm_0.ALMNUM = '001'
                AND fdoc_0.DHORA > ?
        ", [$ultimaHora]);

            Log::info('📦 Total registros nuevos: ' . count($registros));

            $insertados = 0;

            foreach ($registros as $solucion) {
                try {
                    $resultado = DB::connection('mysql')->table('catalogo_soluciones_clientes')->insertOrIgnore([
                        'dseq'        => $solucion->DSEQ,
                        'ditipmv'     => $solucion->DITIPMV,
                        'dnum'        => $solucion->DNUM,
                        'dpar1'       => $solucion->DPAR1,
                        'dhora'       => $solucion->DHORA,
                        'icod'        => $solucion->ICOD,
                        'clicod'      => $solucion->CLICOD,
                        'clidesc10'   => $solucion->CLIDESC10,
                        'aiprecio'    => $solucion->AIPRECIO,
                        'aicant'      => abs($solucion->AICANT),
                        'idescr'      => $solucion->IDESCR,
                        'estatus'     => 1,
                        'created_at'  => now(),
                        'updated_at'  => now()
                    ]);

                    $insertados += $resultado;
                } catch (Exception $e) {
                    Log::error("❌ Error al insertar ICOD {$solucion->ICOD}: " . $e->getMessage());
                }
            }

            Log::info("✅ Sincronización completada. Insertados: {$insertados}");
            Log::info('===============================================================');
        } catch (Exception $e) {
            Log::error('🚨 Error general en la sincronización de soluciones: ' . $e->getMessage());
        }
    }
}
