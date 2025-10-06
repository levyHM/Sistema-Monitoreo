<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SolucionesSyncService;

// php artisan sync:soluciones
class SyncSoluciones extends Command
{
    protected $signature = 'sync:soluciones';
    protected $description = 'Sincroniza soluciones desde la base remota a la local';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $servicio = new SolucionesSyncService();
        $servicio->sync();

        $this->info('✅ Sincronización de soluciones completada.');
    }
}
