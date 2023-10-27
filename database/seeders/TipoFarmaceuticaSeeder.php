<?php

namespace Database\Seeders;

use App\Models\TipoFarmaceutica;
use Illuminate\Database\Seeder;

class TipoFarmaceuticaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoFarmaceutica::create([
            'nombre' => 'Envase'
        ]);

        TipoFarmaceutica::create([
            'nombre' => 'Forma farmaceutica'
        ]);

        TipoFarmaceutica::create([
            'nombre' => 'Concentracion'
        ]);

        TipoFarmaceutica::create([
            'nombre' => 'Contenido'
        ]);

        TipoFarmaceutica::create([
            'nombre' => 'Via administracion'
        ]);
    }
}
