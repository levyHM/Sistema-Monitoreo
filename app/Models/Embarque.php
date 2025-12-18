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
        'id_conductor',
        'ID_RUTA',
        'ID_CAMIONETA',
        'FECHA',
        'ESCANER',
        'FACTURA',
        'CANTIDAD',
        'VALIDACION',
        'id_cliente',
        'url_img',
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
    // Relación con el modelo FacturaParte
    public function facturaPartes()
    {
        // La columna 'embarques_id' es la clave foránea en factura_partes
        return $this->hasMany(FacturaParte::class, 'embarques_id');
    }

    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'id');
    }

    public function conductor()
    {
        return $this->belongsTo(Conductor::class, 'id_conductor', 'id');
    }
}
