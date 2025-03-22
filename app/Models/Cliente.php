<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    // Definir el nombre de la tabla si es diferente al nombre por defecto
    protected $table = 'clientes';

    // Campos que se pueden asignar de manera masiva
    protected $fillable = [
        'id',
        'CLICOD',
        'CLINOM',
        'CLICD',
        'CLIPAR1',
        'CLIPAR7',
        'CLISUCURSAL',
        'CLINOM2'
    ];

    // Opcional: Si tu tabla tiene timestamps, puedes mantener la configuración predeterminada
    public $timestamps = true; // Esto es true por defecto en Laravel
}
