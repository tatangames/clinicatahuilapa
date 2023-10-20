<?php

namespace Database\Seeders;

use App\Models\TipoAntecedente;
use Illuminate\Database\Seeder;

class TipoAntecedentesMedicosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoAntecedente::create([
            'nombre' => 'Antecedentes Médicos'
        ]);

        TipoAntecedente::create([
            'nombre' => 'Complicaciones Agudas en Diábetes'
        ]);

        TipoAntecedente::create([
            'nombre' => 'Enfermedades Crónicas'
        ]);

        TipoAntecedente::create([
            'nombre' => 'Antecedentes Quirúrgicos'
        ]);
    }
}
