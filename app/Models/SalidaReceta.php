<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalidaReceta extends Model
{
    use HasFactory;
    protected $table = 'salida_receta';
    public $timestamps = false;
}
