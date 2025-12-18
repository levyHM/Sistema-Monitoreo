<?php

namespace App\Models;

use App\Models\CatalogoProveedor;
use App\Models\ConceptoRecibo;
use App\Models\Evidencia;
use App\Models\Firma;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recibo extends Model
{
    use HasFactory;

    protected $table = 'recibos';
    protected $primaryKey = 'idrecibos';
    public $timestamps = false;

    protected $fillable = [
        'tipo_recibo',
        'sucursal',
        'fecha',
        'estatus',
        'observaciones',
        'provedores_idprovedores',
    ];

    public function proveedor()
    {
        //return $this->belongsTo(CatalogoProveedor::class, 'provedores_idprovedores');
        return $this->belongsTo(CatalogoProveedor::class, 'provedores_idprovedores', 'idcatalogo_provedores');
    }

    public function firmas()
    {

        return $this->hasMany(Firma::class, 'recibos_idrecibos');
    }

    public function conceptos()
    {
        return $this->hasMany(ConceptoRecibo::class, 'recibos_idrecibos', 'idrecibos');
    }

    public function evidencias()
    {
        return $this->hasMany(Evidencia::class, 'recibos_idrecibos', 'idrecibos');
    }


    public function firma()
    {
        return $this->hasOne(Firma::class, 'recibos_idrecibos');
    }
    public function conceptosEstado()
    {
        return $this->hasMany(Concepto::class, 'recibos_idrecibos', 'idrecibos');
    }
    public function concepto_recibos()
    {
        return $this->hasMany(ConceptoRecibo::class, 'recibos_idrecibos', 'idrecibos');
    }
}
