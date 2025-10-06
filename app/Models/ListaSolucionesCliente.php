<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ListaSolucionesCliente extends Model
{
    protected $table = 'lista_soluciones_clientes';
    protected $primaryKey = 'idlista_soluciones_clientes';
    public $timestamps = true;

    protected $fillable = [
        'reporte_soluciones_clientes_idreporte_soluciones_clientes',
        'catalogo_soluciones_clientes_idCatalogoSolucionesClientes',
        'factura',
        'cantidad',
        'total',
        'observaciones',
        'estatus',
    ];

    /**
     * Relación con el reporte padre
     */
    public function reporte(): BelongsTo
    {
        return $this->belongsTo(
            ReporteSolucionesCliente::class,
            'reporte_soluciones_clientes_idreporte_soluciones_clientes'
        );
    }

    /**
     * Relación con el catálogo de productos
     */
    public function catalogo(): BelongsTo
    {
        return $this->belongsTo(
            CatalogoSolucionesCliente::class,
            'catalogo_soluciones_clientes_idCatalogoSolucionesClientes',
            'idCatalogoSolucionesClientes'
        );
    }
}
