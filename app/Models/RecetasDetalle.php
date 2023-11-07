<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecetasDetalle extends Model
{
    use HasFactory;
    protected $table = 'recetas_detalle';
    public $timestamps = false;
}
