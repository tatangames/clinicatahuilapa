<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoFarmaceutica extends Model
{
    use HasFactory;
    protected $table = 'farmacia_articulo';
    public $timestamps = false;
}
