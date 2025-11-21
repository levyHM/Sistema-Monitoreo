<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvidenciaListaSolucion extends Model
{
    protected $table = 'evidencias_lista_soluciones';
    protected $primaryKey = 'id';

    protected $fillable = [
        'lista_soluciones_clientes_id',
        'archivo',
    ];

    public function solucion()
    {
        return $this->belongsTo(ListaSolucionesCliente::class, 'lista_soluciones_clientes_id', 'idlista_soluciones_clientes');
    }
}
