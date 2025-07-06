<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Camioneta extends Model
{
    use HasFactory;

    protected $fillable = [
        'clave',
        'placas',
        'marca',
    ];
}