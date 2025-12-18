<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteFalta extends Model
{
    use HasFactory;

    protected $table = 'reporte_falta';
    protected $primaryKey = 'idreporte_falta';
    public $incrementing = true;

    protected $fillable = [
        'cantidad',
        'numero_factura',
        'checo',
        'empaco',
        'reporte_faltante_idreporte_faltante',
        'catalogo_idcatalogo',
    ];

    // Relación con reporte_faltante
    public function reporteFaltante()
    {
        return $this->belongsTo(ReporteFaltante::class, 'reporte_faltante_idreporte_faltante');
    }

    // Relación con catalogo_producto
    public function catalogoProducto()
    {
        return $this->belongsTo(CatalogoProducto::class, 'catalogo_idcatalogo');
    }

}
