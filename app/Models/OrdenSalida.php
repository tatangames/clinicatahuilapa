<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenSalida extends Model
{
    use HasFactory;
    protected $table = 'orden_salida';
    public $timestamps = false;
}
