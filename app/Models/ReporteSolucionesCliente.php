<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReporteSolucionesCliente extends Model
{
    protected $table = 'reporte_soluciones_clientes';

    protected $primaryKey = 'idreporte_soluciones_clientes';

    public $timestamps = true;

    protected $fillable = [
        'fecha',
        'catalogo_clientes_idcatalogo_clientes',
        'devolucion',
        'catalogo_tipo_id',
        'firma_cliente',
        'firma_soluciones',
        'firma_credito',
        'total',
        'descuento',
        'subtotal',
        'iva',
        'total_completo',
        'estatus',
        'observaciones',
    ];

    /**
     * Relación con el cliente del catálogo
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(
            CatalogoCliente::class,
            'catalogo_clientes_idcatalogo_clientes',
            'idcatalogo_clientes'
        );
    }
    /**
     * Relación con las soluciones asociadas al reporte
     */
    public function soluciones(): HasMany
    {
        return $this->hasMany(
            ListaSolucionesCliente::class,
            'reporte_soluciones_clientes_idreporte_soluciones_clientes',
            'idreporte_soluciones_clientes'
        );
    }
    public function firmanteCliente()
    {
        return $this->belongsTo(User::class, 'firma_cliente');
    }

    public function firmanteSoluciones()
    {
        return $this->belongsTo(User::class, 'firma_soluciones');
    }

    public function firmanteCredito()
    {
        return $this->belongsTo(User::class, 'firma_credito');
    }

    public function catalogoTipo()
    {
        return $this->belongsTo(CatalogoTipo::class, 'catalogo_tipo_id');
    }
}
