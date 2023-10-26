<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProveedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('tipo_proveedor_id')->unsigned();

            $table->string('nombre', 100);
            $table->string('nombre_comercial', 300)->nullable();
            $table->string('nrc', 100)->nullable();
            $table->string('nit', 100)->nullable();
            $table->string('direccion', 500)->nullable();

            // DATOS DE CONTACTO

            $table->string('departamento_contacto', 300)->nullable();
            $table->string('telefono_fijo', 20)->nullable();
            $table->string('telefono_celular', 20)->nullable();
            $table->string('correo', 150)->nullable();

            $table->foreign('tipo_proveedor_id')->references('id')->on('tipo_proveedor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proveedores');
    }
}
