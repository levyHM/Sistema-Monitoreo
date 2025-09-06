<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ProveedorSyncService;

class SyncProveedoresCommand extends Command
{
    protected $signature = 'sync:proveedores';
    protected $description = 'Sincroniza proveedores desde db152jigafra.fprv hacia catalogo_provedores';

    public function handle()
    {
        app(ProveedorSyncService::class)->sync();
        $this->info('✅ Proveedores sincronizados exitosamente.');
    }
}
