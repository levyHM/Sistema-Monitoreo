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
        'motivo_faltante_id',
        'solucion',
        'catalogo_reporte_faltante_tipo_id',
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

    /**
     * Relación con catálogo de motivo de faltante
     */
    public function motivoFaltante()
    {
        return $this->belongsTo(CatalogoMotivoFaltante::class, 'motivo_faltante_id', 'id');
    }
    /**
     * Relación con catálogo de tipo de faltante
     */
    public function catalogoTipoFaltante()
    {
        return $this->belongsTo(CatalogoReporteFaltanteTipo::class, 'catalogo_reporte_faltante_tipo_id', 'id');
    }
}
