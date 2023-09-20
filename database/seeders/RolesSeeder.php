<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Administrador
        $roleAdmin = Role::create(['name' => 'admin']);

        // Archivo
        $roleArchivo = Role::create(['name' => 'archivo']);

        // Enfermeria
        $roleEnfermeria = Role::create(['name' => 'enfermeria']);

        // Doctora
        $roleDoctora = Role::create(['name' => 'doctora']);

        // Farmacia
        $roleFarmacia = Role::create(['name' => 'farmacia']);


        // solo para administrador
        Permission::create(['name' => 'sidebar.roles.y.permisos', 'description' => 'sidebar seccion roles y permisos'])->syncRoles($roleAdmin);
       // Permission::create(['name' => 'sidebar.catalogo', 'description' => 'contenedor de catalogo'])->syncRoles($roleInventario);
    }


}
