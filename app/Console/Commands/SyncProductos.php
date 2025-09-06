<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ProductoSyncService;

class SyncProductos extends Command
{
    protected $signature = 'sync:productos';
    protected $description = 'Sincroniza productos desde la base remota a la local';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $servicio = new ProductoSyncService();
        $servicio->sync();

        $this->info('✅ Sincronización de productos completada.');
    }
}
