<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CatalogoProductoSyncService;

class SyncCatalogoProducto extends Command
{
    /**
     * The name and signature of the console command.
     *
     * php artisan sync:catalogo-producto
     */
    protected $signature = 'sync:catalogo-producto';

    /**
     * The console command description.
     */
    protected $description = 'Sincroniza la tabla catalogo_producto desde la base externa';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Iniciando sincronización de catalogo_producto...');

        $syncService = new CatalogoProductoSyncService();
        $syncService->sync();

        $this->info('Sincronización de catalogo_producto completada exitosamente.');
    }
}
