<?php

namespace Database\Seeders;

use App\Models\Estado_Civil;
use Illuminate\Database\Seeder;

class EstadoCivilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Estado_Civil::create([
            'nombre' => 'SOLTERO/A'
        ]);

        Estado_Civil::create([
            'nombre' => 'CASADO/A'
        ]);

        Estado_Civil::create([
            'nombre' => 'VIUDO/A'
        ]);

        Estado_Civil::create([
            'nombre' => 'DIVORCIADO/A'
        ]);
    }
}
