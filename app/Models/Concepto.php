<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Concepto extends Model
{
    protected $table = 'concepto';
    protected $primaryKey = 'idconcepto';
    public $timestamps = false;

    protected $fillable = [
        'devolucion',
        'faltante',
        'sobrante',
        'recibos_idrecibos',
    ];

    public function recibo()
    {
        return $this->belongsTo(Recibo::class, 'recibos_idrecibos', 'idrecibos');
    }
}
