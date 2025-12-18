<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';
    protected $primaryKey = 'idproductos';
    public $timestamps = false;

    protected $fillable = [
        'icod',
        'icodprv',
        'idescrip',
        'estatus',
    ];

    // Relación: un producto puede aparecer en muchos conceptos de recibo
    public function conceptos()
    {
        return $this->hasMany(ConceptoRecibo::class, 'productos_idproductos');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'productos_idproductos', 'idproductos');
    }
}
