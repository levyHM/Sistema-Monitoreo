<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CatalogoCliente extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'catalogo_clientes';

    // Clave primaria
    protected $primaryKey = 'idcatalogo_clientes';

    // Si tu clave primaria NO es incremental o no es tipo int, especifica esto:
    // public $incrementing = false;
    // protected $keyType = 'string';

    // Laravel asume que hay created_at y updated_at.
    // Si tu tabla no las tiene:
    public $timestamps = false;

    // Campos que se pueden asignar en masa
    protected $fillable = [
        'clicod',
        'clinom',
        'clipar1',
        'clidesc10',
        'observaciones',
        'estatus',
    ];

    /**
     * Relación con ReporteSolucionesCliente
     */
    public function reportes()
    {
        return $this->hasMany(
            ReporteSolucionesCliente::class,
            'catalogo_clientes_idcatalogo_clientes',
            'idcatalogo_clientes'
        );
    }
}
