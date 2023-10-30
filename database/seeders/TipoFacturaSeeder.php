<?php

namespace Database\Seeders;

use App\Models\TipoFactura;
use Illuminate\Database\Seeder;

class TipoFacturaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoFactura::create([
            'nombre' => 'Consumidor Final'
        ]);

        TipoFactura::create([
            'nombre' => 'Donaciones'
        ]);
    }
}
