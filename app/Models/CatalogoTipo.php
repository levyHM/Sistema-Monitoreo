<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatalogoTipo extends Model
{
    protected $table = 'catalogo_tipo';

    protected $fillable = ['nombre', 'estatus'];

    public function productos()
    {
        return $this->hasMany(Producto::class, 'garantia_id');
    }
}

