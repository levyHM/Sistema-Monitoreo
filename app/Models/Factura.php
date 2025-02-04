<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;

    protected $table = 'facturas';
    
    protected $fillable = [
        'DITIPMV',
        'DNUM',
        'DFECHA',
        'CLICOD',
        'DPAR1',
        'DHORA',
        'SERIE',
        'CAPTURA',
        'ESTATUS',
    ];
}
