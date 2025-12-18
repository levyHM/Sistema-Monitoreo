<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClienteSyncService
{
    public function sync()
    {
        $query = DB::connection('mysql2')->table('db152jigafra.fcli')
            ->select('CLICOD', 'CLINOM', 'CLIPAR1', 'CLIDESC10')
            ->where('CLIPAR1', '<>', '1Z99');

        Log::info('SQL generado: ' . $query->toSql());

        $clientes = $query->get();

        foreach ($clientes as $cliente) {
            // Verificar si ya existe en la base local
            $existe = DB::connection('mysql')->table('catalogo_clientes')
                ->where('clicod', $cliente->CLICOD)
                ->exists();

            if (!$existe) {
                DB::connection('mysql')->table('catalogo_clientes')->insert([
                    'clicod' => $cliente->CLICOD,
                    'clinom' => $cliente->CLINOM,
                    'clipar1' => $cliente->CLIPAR1,
                    'clidesc10' => $cliente->CLIDESC10,
                    'estatus' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        Log::info('Clientes copiados exitosamente.');
    }
}
