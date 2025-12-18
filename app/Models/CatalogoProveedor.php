<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogoProveedor extends Model
{
    use HasFactory;

    protected $table = 'catalogo_provedores';

    protected $primaryKey = 'idcatalogo_provedores';

    public $timestamps = true;

    protected $fillable = [
        'prvcod',
        'prvnom',
        'estatus',
    ];

    // Relaciones
    public function recibos()
    {
        return $this->hasMany(Recibo::class, 'provedores_idprovedores');
    }
}
