<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteFaltante extends Model
{
    use HasFactory;

    protected $table = 'reporte_faltante';
    protected $primaryKey = 'idreporte_faltante';
    public $incrementing = true;

    protected $fillable = [
        'fecha',
        'recibe_reporte',
        'catalogo_faltante_idcatalogo_faltante',
        'motivo_faltante',
        'solucion',
        'procede',
        'cambio_fisico',
        'nc_servicio',
        'user_id_registro',
        'user_id_autorizo',
        'observaciones',
        'estatus',
    ];

    // Relación con catalogo_faltante
    public function catalogoFaltante()
    {
        return $this->belongsTo(CatalogoFaltante::class, 'catalogo_faltante_idcatalogo_faltante');
    }

    // Relación con reporte_falta
    public function reportesFalta()
    {
        return $this->hasMany(ReporteFalta::class, 'reporte_faltante_idreporte_faltante');
    }

    public function userAutorizo()
    {
        return $this->belongsTo(User::class, 'user_id_autorizo');
    }
}
