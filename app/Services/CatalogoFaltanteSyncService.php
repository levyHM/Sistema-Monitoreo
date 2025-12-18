<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CatalogoFaltanteSyncService
{
    /**
     * Sincroniza los clientes desde la base externa hacia catalogo_faltante
     * y actualiza el estatus de los registros faltantes.
     */
    public function sync()
    {
        // 1️⃣ Consultar los clientes desde la base externa
        $query = DB::connection('mysql2')->table('db152jigafra.fcli as fcli_0')
            ->select('CLICOD', 'CLINOM', 'CLIPAR1')
            ->where('CLIPAR1', '<>', '1Z99');

        Log::info('SQL generado: ' . $query->toSql());

        $clientes = $query->get();

        // 2️⃣ Guardar todos los CLICOD de la fuente externa
        $codigosExternos = [];

        foreach ($clientes as $cli) {
            $codigosExternos[] = $cli->CLICOD;

            $data = [
                'codigo'        => $cli->CLICOD,        // clave única
                'codigo_nombre' => $cli->CLINOM,
                'zona'          => $cli->CLIPAR1,
                'estatus'       => 1,                   // activo
                'updated_at'    => now(),
                'created_at'    => now(),
            ];

            // Insertar o actualizar en la tabla local
            DB::connection('mysql')->table('catalogo_faltante')->updateOrInsert(
                ['codigo' => $cli->CLICOD], // buscar por 'codigo'
                $data
            );
        }

        // 3️⃣ Marcar como faltantes los registros locales que ya no existen en la fuente externa
        DB::connection('mysql')->table('catalogo_faltante')
            ->whereNotIn('codigo', $codigosExternos)
            ->update(['estatus' => 0, 'updated_at' => now()]);

        Log::info('Catalogo_faltante sincronizado exitosamente y registros faltantes actualizados.');
    }
}
