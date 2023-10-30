<?php

namespace Database\Seeders;

use App\Models\FuenteFinanciamiento;
use Illuminate\Database\Seeder;

class FuenteFinanciamientoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FuenteFinanciamiento::create([
            'nombre' => 'Materiales FUNDEL'
        ]);

        FuenteFinanciamiento::create([
            'nombre' => 'Materiales COVID'
        ]);

        FuenteFinanciamiento::create([
            'nombre' => 'Fondos PROPIOS'
        ]);
    }
}
