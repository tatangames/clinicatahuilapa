<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotasPaciente extends Model
{
    use HasFactory;
    protected $table = 'notas_paciente';
    public $timestamps = false;
}
