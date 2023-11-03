<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenSalidaDetalle extends Model
{
    use HasFactory;
    protected $table = 'orden_salida_detalle';
    public $timestamps = false;
}
