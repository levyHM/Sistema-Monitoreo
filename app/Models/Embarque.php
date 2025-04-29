<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Embarque extends Model
{
    use HasFactory;

    // Especifica el nombre de la tabla si no es la convención plural del modelo
    protected $table = 'embarques';

    // Define los campos que pueden ser asignados masivamente
    protected $fillable = [
        'ID_OPERADOR', 
        'ID_RUTA', 
        'ID_CAMIONETA', 
        'FECHA', 
        'ESCANER',
        'FACTURA',  
        'CANTIDAD', 
        'VALIDACION', 
        'CLIENTE', 
        'SUCURSAL', 
        'HORA_DE_LLEGADA', 
        'HORA_DE_SALIDA', 
        'OBSERVACIONES', 
        'ESTATUS'
    ];

    // Si no quieres que se asignen automáticamente los campos created_at y updated_at, puedes deshabilitar estos campos:
    public $timestamps = true; // Esto está habilitado por defecto, si no quieres timestamps, pon false.

    // Si tu campo de fecha no usa el formato 'Y-m-d H:i:s' por defecto, puedes especificarlo
    protected $dates = [
        'FECHA', 
        'HORA_DE_LLEGADA', 
        'HORA_DE_SALIDA'
    ];

    /*
    // Si el campo ESCANER debe ser único, puedes validarlo en el modelo también (aunque esto se realiza más fácilmente en un controlador)
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Verificar si el campo 'ESCANER' ya existe en la base de datos
            if (self::where('ESCANER', $model->ESCANER)->exists()) {
                throw new \Exception("El código de escáner ya existe.");
            }
        });
    }
    */
}
