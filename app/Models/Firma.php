<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Asegúrate de importar el modelo User

class Firma extends Model
{
    use HasFactory;

    protected $table = 'firma';

    protected $primaryKey = 'idfirma';

    public $timestamps = true;

    protected $fillable = [
        'id_user',
        'catalogo_firma_idcatalogo_firma',
        'observaciones',
        'estatus',
        'recibos_idrecibos',
    ];

    // 🔗 Relaciones Eloquent

    public function tipoFirma()
    {
        return $this->belongsTo(CatalogoFirma::class, 'catalogo_firma_idcatalogo_firma');
    }

    public function recibo()
    {
        return $this->belongsTo(Recibo::class, 'recibos_idrecibos');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_user', 'id'); // Asegúrate que 'id_user' sea el ID del usuario en la tabla firma
    }
}
