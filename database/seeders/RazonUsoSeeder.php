<?php

namespace Database\Seeders;

use App\Models\Motivo;
use Illuminate\Database\Seeder;

class RazonUsoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Motivo::create([
            'nombre' => 'CONSULTA'
        ]);

        Motivo::create([
            'nombre' => 'VACUNAS'
        ]);

        Motivo::create([
            'nombre' => 'CONSEJERIA'
        ]);

        Motivo::create([
            'nombre' => 'COACHING'
        ]);

        Motivo::create([
            'nombre' => 'CAJA'
        ]);
    }
}
