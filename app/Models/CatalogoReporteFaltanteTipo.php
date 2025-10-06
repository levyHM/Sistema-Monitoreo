<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatalogoReporteFaltanteTipo extends Model
{
    protected $table = 'catalogo_reporte_faltante_tipo';

    protected $fillable = [
        'nombre',
        'estatus',
    ];

    // Opcional: acceso legible al estatus
    public function estatusTexto(): string
    {
        return match($this->estatus) {
            0 => 'Inactivo',
            1 => 'Activo',
            2 => 'Archivado',
            default => 'Desconocido',
        };
    }
}
