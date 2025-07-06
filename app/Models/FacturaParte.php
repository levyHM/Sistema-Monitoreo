<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacturaParte extends Model
{
    use HasFactory;

    protected $table = 'factura_partes';

    protected $fillable = [
        'embarques_id',
        'factura',
        'parte_num',
        'estado_validacion',
        'observaciones',
    ];

    public function embarque()
    {
        return $this->belongsTo(Embarque::class, 'embarques_id');
    }

    
}