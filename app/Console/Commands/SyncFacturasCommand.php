<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DataService;

class SyncFacturasCommand extends Command
{
    protected $signature = 'facturas:sync';
    protected $description = 'Sincroniza datos de facturas desde la segunda base';

    public function handle()
    {
        app(DataService::class)->copyOrUpdateData();
        $this->info('Sincronización completada');
    }
}
