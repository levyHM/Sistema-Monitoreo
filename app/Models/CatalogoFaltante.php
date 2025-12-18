<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogoFaltante extends Model
{
    use HasFactory;

    protected $table = 'catalogo_faltante';
    protected $primaryKey = 'idcatalogo_faltante';
    public $incrementing = true;

    protected $fillable = [
        'codigo',
        'codigo_nombre',
        'zona',
        'estatus',
    ];

    // Relación con reporte_faltante
    public function reportesFaltante()
    {
        return $this->hasMany(ReporteFaltante::class, 'catalogo_faltante_idcatalogo_faltante');
    }
}
