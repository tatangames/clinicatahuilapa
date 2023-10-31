<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntradaMedicamentoDetalle extends Model
{
    use HasFactory;
    protected $table = 'entrada_medicamento_detalle';
    public $timestamps = false;
}
