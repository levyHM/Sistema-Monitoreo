<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CatalogoFaltanteSyncService;

class SyncCatalogoFaltante extends Command
{
    /**
     * The name and signature of the console command.
     *
     * php artisan sync:catalogo-faltante
     */
    protected $signature = 'sync:catalogo-faltante';

    /**
     * The console command description.
     */
    protected $description = 'Sincroniza la tabla catalogo_faltante desde la base externa';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Iniciando sincronización de catalogo_faltante...');

        $syncService = new CatalogoFaltanteSyncService();
        $syncService->sync();

        $this->info('Sincronización completada exitosamente.');
    }
}
