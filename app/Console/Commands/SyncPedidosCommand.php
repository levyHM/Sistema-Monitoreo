<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DataServicePedidos;
use Illuminate\Support\Facades\Log;

class SyncPedidosCommand extends Command
{
    protected $signature = 'sync:pedidos';
    protected $description = 'Sincroniza los pedidos desde mysql2 hacia mysql';

    public function handle(): void
    {
        Log::info('Comando sync:pedidos ejecutado');
        $service = new DataServicePedidos();
        $service->copyOrUpdateData();
        Log::info('Pedidos sincronizados exitosamente');
    }
}