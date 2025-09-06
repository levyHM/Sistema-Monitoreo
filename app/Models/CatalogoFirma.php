<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogoFirma extends Model
{
    use HasFactory;

    protected $table = 'catalogo_firma';

    protected $primaryKey = 'idcatalogo_firma';

    public $timestamps = true;

    protected $fillable = [
        'descripcion',
        'observaciones',
        'estatus',
    ];

    // 🔗 Relación con firmas
    public function firmas()
    {
        return $this->hasMany(Firma::class, 'catalogo_firma_idcatalogo_firma');
    }
}
