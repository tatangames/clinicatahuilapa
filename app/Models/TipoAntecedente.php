<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoAntecedente extends Model
{
    use HasFactory;
    protected $table = 'tipo_antecedente';
    public $timestamps = false;
}
