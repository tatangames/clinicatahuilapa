<?php

namespace Database\Seeders;

use App\Models\MotivoFarmacia;
use Illuminate\Database\Seeder;

class MotivoSalidaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MotivoFarmacia::create([
            'nombre' => 'Producto vencido o dañado'
        ]);

        MotivoFarmacia::create([
            'nombre' => 'Producto en prestamo'
        ]);

        MotivoFarmacia::create([
            'nombre' => 'Producto inexistente'
        ]);

        MotivoFarmacia::create([
            'nombre' => 'Se utilizo en paciente'
        ]);

        MotivoFarmacia::create([
            'nombre' => 'Salida'
        ]);

        MotivoFarmacia::create([
            'nombre' => 'Emergencia'
        ]);
        MotivoFarmacia::create([
            'nombre' => 'Tester'
        ]);

        MotivoFarmacia::create([
            'nombre' => 'Uso de Laboratorio'
        ]);

        MotivoFarmacia::create([
            'nombre' => 'Donación'
        ]);

        MotivoFarmacia::create([
            'nombre' => 'Sala RX'
        ]);
    }
}
