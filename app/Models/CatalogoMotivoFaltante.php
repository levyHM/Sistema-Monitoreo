<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogoMotivoFaltante extends Model
{
    use HasFactory;

    protected $table = 'catalogo_motivo_faltante';
    protected $primaryKey = 'id';

    protected $fillable = [
        'descripcion',
        'detalle',
        'estatus',
    ];

    /**
     * Relación con los reportes que usan este motivo
     */
    public function reportes()
    {
        return $this->hasMany(ReporteFaltante::class, 'motivo_faltante_id', 'id');
    }
}
