<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consulta_Paciente extends Model
{
    use HasFactory;
    protected $table = 'consulta_paciente';
    public $timestamps = false;
}
