<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConceptoRecibo extends Model
{
    use HasFactory;

    protected $table = 'concepto_recibos';
    protected $primaryKey = 'idconcepto_recibos';
    public $timestamps = false;

    protected $fillable = [
        'numero_factura',
        'cantidad',
        'productos_idproductos',
        'facturado',
        'fisico',
        'observaciones',
        'recibos_idrecibos',
    ];

    // Relaciones
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'productos_idproductos');
    }

    public function recibo()
    {
        return $this->belongsTo(Recibo::class, 'recibos_idrecibos');
    }
}
