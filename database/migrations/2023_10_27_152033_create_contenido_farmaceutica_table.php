<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContenidoFarmaceuticaTable extends Migration
{
    /**
     * TIPO FARMACEUTICA
     *
     * 1- ENVASE
     * 2- FORMA FARMACEUTICA
     * 3- CONCENTRACION
     * 4- CONTENIDO
     * 5- VIA ADMINISTRACION
     *
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contenido_farmaceutica', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tipo_farmaceutica_id')->unsigned();
            $table->string('nombre', 300);

            $table->foreign('tipo_farmaceutica_id')->references('id')->on('tipo_farmaceutica');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contenido_farmaceutica');
    }
}
