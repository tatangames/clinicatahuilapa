<?php

namespace Database\Seeders;

use App\Models\ViaReceta;
use Illuminate\Database\Seeder;

class ViaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ViaReceta::create([
            'nombre' => 'ORAL'
        ]);

        ViaReceta::create([
            'nombre' => 'INTRAMUSCULAR'
        ]);

        ViaReceta::create([
            'nombre' => 'NASAL'
        ]);

        ViaReceta::create([
            'nombre' => 'INHALADO'
        ]);

        ViaReceta::create([
            'nombre' => 'OCULAR'
        ]);
        ViaReceta::create([
            'nombre' => 'TOPICA'
        ]);

        ViaReceta::create([
            'nombre' => 'INHALADO CON ESPACIADOR DE VOLUMEN'
        ]);

        ViaReceta::create([
            'nombre' => 'AEROSOL INHALADO DE POLVO SECO'
        ]);

        ViaReceta::create([
            'nombre' => 'SUSPENSION PARA NEBULIZAR'
        ]);

        ViaReceta::create([
            'nombre' => 'SOLUCION ACUOSA'
        ]);

        ViaReceta::create([
            'nombre' => 'OTICA'
        ]);

        ViaReceta::create([
            'nombre' => 'TOPICA ORAL'
        ]);
        ViaReceta::create([
            'nombre' => 'LIOFILIZADO PARA DISOLVER'
        ]);

        ViaReceta::create([
            'nombre' => 'IRRIGACION NASAL'
        ]);

        ViaReceta::create([
            'nombre' => 'VAGINAL'
        ]);

        ViaReceta::create([
            'nombre' => 'SUBCUTANEA'
        ]);

        ViaReceta::create([
            'nombre' => 'INTRAVENOSO'
        ]);

        ViaReceta::create([
            'nombre' => 'RECTAL'
        ]);

    }
}
