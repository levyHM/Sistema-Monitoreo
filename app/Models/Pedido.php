<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'PEFECHA',
        'PENUM',
        'PEALMACEN',
        'PEPAR0',
        'PEPAR1',
        'SUCURSAL',
        'SERIE',
        'CAPTURA',
        'ESTATUS',
    ];
}
