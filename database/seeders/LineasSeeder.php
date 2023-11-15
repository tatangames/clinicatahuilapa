<?php

namespace Database\Seeders;

use App\Models\Linea;
use Illuminate\Database\Seeder;

class LineasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Linea::create([
            'nombre' => 'Medicamentos'
        ]);

        Linea::create([
            'nombre' => 'Materiales'
        ]);

        Linea::create([
            'nombre' => 'Equipo Hospitalario'
        ]);
        Linea::create([
            'nombre' => 'Servicios de Consulta'
        ]);
        Linea::create([
            'nombre' => 'Servicios de Imagenes Diagnosticas'
        ]);
        Linea::create([
            'nombre' => 'Servicios de Laboratorio Clinico'
        ]);

        Linea::create([
            'nombre' => 'Kits'
        ]);

        Linea::create([
            'nombre' => 'Perfiles de Laboratorio'
        ]);

        Linea::create([
            'nombre' => 'Reactivos'
        ]);
    }
}
