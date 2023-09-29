<?php

namespace Database\Seeders;

use App\Models\SalasEspera;
use Illuminate\Database\Seeder;

class SalaEsperaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SalasEspera::create([
            'nombre' => 'Consultorio'
        ]);

        SalasEspera::create([
            'nombre' => 'Enfermeria'
        ]);

    }
}
