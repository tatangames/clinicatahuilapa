<?php

namespace Database\Seeders;

use App\Models\Tipo_Paciente;
use Illuminate\Database\Seeder;

class TipoPacienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tipo_Paciente::create([
            'nombre' => 'Adulto'
        ]);

        Tipo_Paciente::create([
            'nombre' => 'Niño/a'
        ]);
    }
}
