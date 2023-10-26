<?php

namespace Database\Seeders;

use App\Models\TipoProveedor;
use Illuminate\Database\Seeder;

class TipoProveedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoProveedor::create([
            'nombre' => 'Persona Juridica'
        ]);

        TipoProveedor::create([
            'nombre' => 'Persona Natural'
        ]);

        TipoProveedor::create([
            'nombre' => 'Internacional'
        ]);
    }
}
