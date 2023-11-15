<?php

namespace Database\Seeders;

use App\Models\Tipo_Documento;
use Illuminate\Database\Seeder;

class TipoDocumentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tipo_Documento::create([
            'nombre' => 'CEDULA'
        ]);

        Tipo_Documento::create([
            'nombre' => 'ID'
        ]);
        Tipo_Documento::create([
            'nombre' => 'DUI'
        ]);

        Tipo_Documento::create([
            'nombre' => 'PARTIDA DE NACIMIENTO'
        ]);

        Tipo_Documento::create([
            'nombre' => 'PASAPORTE'
        ]);

        Tipo_Documento::create([
            'nombre' => 'CARNET DE RESIDENTE'
        ]);

        Tipo_Documento::create([
            'nombre' => 'DPI'
        ]);
    }
}
