<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evidencia extends Model
{
    use HasFactory;

    // Si tu tabla se llama diferente, se especifica aquí, pero en este caso Laravel asumirá 'evidencias' automáticamente.
    
    // Para asignación masiva, puedes definir los campos 'fillable':
    protected $fillable = [
        'url',
        'estatus',
        'recibos_idrecibos',
    ];

    // Si la clave primaria no es 'id' o no es autoincrementable, debes indicarlo
    protected $primaryKey = 'idevidencias';
    public $incrementing = false;  // Si tu ID no es auto incrementable
    protected $keyType = 'int';    // Tipo del ID

    // Si no usas timestamps (created_at, updated_at)
    public $timestamps = false;

    // Relación con recibos (si quieres definirla)
    public function recibo()
    {
        return $this->belongsTo(Recibo::class, 'recibos_idrecibos', 'idrecibos');
    }
}
