<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CatalogoSolucionesCliente extends Model
{
    use HasFactory;

    protected $table = 'catalogo_soluciones_clientes';

    protected $primaryKey = 'idCatalogoSolucionesClientes';

    protected $fillable = [
        'ditipmv',
        'dnum',
        'dpar1',
        'dhora',
        'icod',
        'clicod',
        'clidesc10',
        'aiprecio',
        'aicant',
        'idescr',
        'observaciones',
        'estatus',
    ];

    // Helper para calcular el total del ítem
    public function getTotalItemAttribute()
    {
        return ($this->aiprecio ?? 0) * ($this->aicant ?? 0);
    }
}
