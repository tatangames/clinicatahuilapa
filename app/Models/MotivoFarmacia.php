<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MotivoFarmacia extends Model
{
    use HasFactory;
    protected $table = 'motivo_farmacia';
    public $timestamps = false;
}
