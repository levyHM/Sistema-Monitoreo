<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogoSucursal extends Model
{
    use HasFactory;

    // Nombre de la tabla (opcional si sigue la convención)
    protected $table = 'catalogo_sucursales';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'codigo',
        'nombre',
        'estatus',
    ];

    // Relación inversa: Usuarios de esta sucursal
    public function users()
    {
        return $this->hasMany(User::class, 'catalogo_sucursales_id');
    }
}
