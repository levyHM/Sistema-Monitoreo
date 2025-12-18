<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conductor extends Model
{
    use HasFactory;

    // Fuerza a usar la tabla en español
    protected $table = 'conductores';

    protected $fillable = [
        'clave',
        'operador',
        'observaciones',
    ];

    public function embarques()
    {
        return $this->hasMany(Embarque::class, 'id_conductor');
    }
}
