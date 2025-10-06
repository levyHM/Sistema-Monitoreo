<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ClienteSyncService;

//php artisan sync:clientes
class SyncClientes extends Command
{
    protected $signature = 'sync:clientes';
    protected $description = 'Sincroniza clientes desde la base remota a la local';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $servicio = new ClienteSyncService();
        $servicio->sync();

        $this->info('✅ Sincronización de clientes completada.');
    }
}
