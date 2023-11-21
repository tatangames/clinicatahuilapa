<?php

namespace Database\Seeders;

use App\Models\AntecedentesMedicos;
use Illuminate\Database\Seeder;

class AntecedentesMedicosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // ANTECENDENTES MEDICOS

        AntecedentesMedicos::create([
            'tipo_id' => '1',
            'nombre' => 'Hipertensión arterial',
        ]);

        AntecedentesMedicos::create([
            'tipo_id' => '1',
            'nombre' => 'Endócrinos',
        ]);

        AntecedentesMedicos::create([
            'tipo_id' => '1',
            'nombre' => 'Pulmonares',
        ]);

        AntecedentesMedicos::create([
            'tipo_id' => '1',
            'nombre' => 'Diábetes',
        ]);

        AntecedentesMedicos::create([
            'tipo_id' => '1',
            'nombre' => 'Cáncer',
        ]);

        AntecedentesMedicos::create([
            'tipo_id' => '1',
            'nombre' => 'Fumador/a',
        ]);


        // COMPLICACIONES AGUDAS EN DIABETES

        AntecedentesMedicos::create([
            'tipo_id' => '2',
            'nombre' => 'Hipoglucemia',
        ]);

        AntecedentesMedicos::create([
            'tipo_id' => '2',
            'nombre' => 'Cetoacedocis',
        ]);

        AntecedentesMedicos::create([
            'tipo_id' => '2',
            'nombre' => 'Estado Hiperosmolar',
        ]);


        // ENFERMEDADES CRONICAS

        AntecedentesMedicos::create([
            'tipo_id' => '3',
            'nombre' => 'Nefropatia',
        ]);


        AntecedentesMedicos::create([
            'tipo_id' => '3',
            'nombre' => 'Neuropatía Diabética',
        ]);

        AntecedentesMedicos::create([
            'tipo_id' => '3',
            'nombre' => 'Retinopatía Diabética',
        ]);

        AntecedentesMedicos::create([
            'tipo_id' => '3',
            'nombre' => 'Cardiopatia',
        ]);


        AntecedentesMedicos::create([
            'tipo_id' => '3',
            'nombre' => 'Tiroideopatías',
        ]);



        // ANTECEDENTES QUIRURJICOS

        AntecedentesMedicos::create([
            'tipo_id' => '4',
            'nombre' => 'Apendicectonía',
        ]);

        AntecedentesMedicos::create([
            'tipo_id' => '4',
            'nombre' => 'Colicestomía',
        ]);

        AntecedentesMedicos::create([
            'tipo_id' => '4',
            'nombre' => 'Cesareas',
        ]);

        AntecedentesMedicos::create([
            'tipo_id' => '4',
            'nombre' => 'Esterilización quirúrgica',
        ]);

        AntecedentesMedicos::create([
            'tipo_id' => '4',
            'nombre' => 'Cirugía de mama',
        ]);


    }
}
