<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntradaMedicamento extends Model
{
    use HasFactory;
    protected $table = 'entrada_medicamento';
    public $timestamps = false;
}
