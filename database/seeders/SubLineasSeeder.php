<?php

namespace Database\Seeders;

use App\Models\SubLinea;
use Illuminate\Database\Seeder;

class SubLineasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SubLinea::create([
            'nombre' => 'Hospitalarios'
        ]);

        SubLinea::create([
            'nombre' => 'OTC'
        ]);

        SubLinea::create([
            'nombre' => 'Hematologia'
        ]);

        SubLinea::create([
            'nombre' => 'Quimica Clinica'
        ]);

        SubLinea::create([
            'nombre' => 'Inmunologia'
        ]);

        SubLinea::create([
            'nombre' => 'Endocrinologia'
        ]);

        SubLinea::create([
            'nombre' => 'Drogas de Abuso'
        ]);
        SubLinea::create([
            'nombre' => 'Bacteriologia'
        ]);

        SubLinea::create([
            'nombre' => 'Coagulacion'
        ]);

        SubLinea::create([
            'nombre' => 'Coproanalisis'
        ]);

        SubLinea::create([
            'nombre' => 'Uroanalisis'
        ]);

        SubLinea::create([
            'nombre' => 'Banco de Sangre'
        ]);

        SubLinea::create([
            'nombre' => 'Gestión de Laboratorio Diversas'
        ]);
        SubLinea::create([
            'nombre' => 'Suturas Medicas'
        ]);

        SubLinea::create([
            'nombre' => 'Tubos Endotraqueales'
        ]);

        SubLinea::create([
            'nombre' => 'Sondas'
        ]);

        SubLinea::create([
            'nombre' => 'Vendas Medicas'
        ]);

        SubLinea::create([
            'nombre' => 'Suministros de Oficina'
        ]);

        SubLinea::create([
            'nombre' => 'Suministros de Mantenimiento'
        ]);
        SubLinea::create([
            'nombre' => 'Suministros Varios'
        ]);

        SubLinea::create([
            'nombre' => 'Ninguna'
        ]);
        SubLinea::create([
            'nombre' => 'Materiales Medicos Quirurgicos'
        ]);
        SubLinea::create([
            'nombre' => 'Materiales de Radiologia e Imagenes'
        ]);
        SubLinea::create([
            'nombre' => 'Materiales de Laboratorio Clinico'
        ]);

        SubLinea::create([
            'nombre' => 'Otros Inventarios'
        ]);

        SubLinea::create([
            'nombre' => 'Servicios de Laboratorio Clinicos'
        ]);

        SubLinea::create([
            'nombre' => 'Servicios de Radiologia e Imagen'
        ]);

        SubLinea::create([
            'nombre' => 'Alimentación (Dietas)'
        ]);

        SubLinea::create([
            'nombre' => 'Servicios de Terapia Respiratoria'
        ]);

        SubLinea::create([
            'nombre' => 'Servicios de Enfermeria'
        ]);

        SubLinea::create([
            'nombre' => 'Servicios de Consulta de Emergencia'
        ]);
        SubLinea::create([
            'nombre' => 'Paquetes Quirurgicos'
        ]);

        SubLinea::create([
            'nombre' => 'Habitaciones'
        ]);
        SubLinea::create([
            'nombre' => 'Insumos Indirectos (Medicamentos)'
        ]);

        SubLinea::create([
            'nombre' => 'Insumos Indirectos (Materiales)'
        ]);
        SubLinea::create([
            'nombre' => 'Anestesicos y Gases'
        ]);

        SubLinea::create([
            'nombre' => 'Roperia'
        ]);
        SubLinea::create([
            'nombre' => 'Salas de Investigación'
        ]);

        SubLinea::create([
            'nombre' => 'Instrumental'
        ]);
        SubLinea::create([
            'nombre' => 'Equipo Médico'
        ]);

        SubLinea::create([
            'nombre' => 'Servicios Varios'
        ]);

        SubLinea::create([
            'nombre' => 'Alquiler de Locales'
        ]);

        SubLinea::create([
            'nombre' => 'Abdomen'
        ]);

        SubLinea::create([
            'nombre' => 'Cabeza'
        ]);
        SubLinea::create([
            'nombre' => 'Columna y Pelvis'
        ]);
        SubLinea::create([
            'nombre' => 'Estudios Especiales'
        ]);
        SubLinea::create([
            'nombre' => 'Extremidad Inferior'
        ]);
        SubLinea::create([
            'nombre' => 'Extremidad Superior'
        ]);
        SubLinea::create([
            'nombre' => 'Rayos X'
        ]);
        SubLinea::create([
            'nombre' => 'Torax'
        ]);

        SubLinea::create([
            'nombre' => 'Ultrasonografia'
        ]);

        SubLinea::create([
            'nombre' => 'Descuentos Especiales'
        ]);

        SubLinea::create([
            'nombre' => 'Suministros de Limpieza'
        ]);

        SubLinea::create([
            'nombre' => 'Suministros de Consumo'
        ]);
    }
}
