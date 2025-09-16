<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogoProducto extends Model
{
    use HasFactory;

    protected $table = 'catalogo_producto';
    protected $primaryKey = 'idcatalogoproducto';
    public $incrementing = true;

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
    ];

    // Relación con reporte_falta
    public function reportesFalta()
    {
        return $this->hasMany(ReporteFalta::class, 'catalogo_idcatalogo');
    }
}
