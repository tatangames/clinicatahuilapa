<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticuloMedicamentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articulo_medicamento', function (Blueprint $table) {
            $table->id();


            $table->bigInteger('farmacia_articulo_id')->unsigned();

            // CONTENDIO FARMACEUTICA

            // envase
            // forma farmaceutica
            // concentracion
            // contenido
            // via administracion
            $table->bigInteger('con_far_envase_id')->unsigned();
            $table->bigInteger('con_far_forma_id')->unsigned();
            $table->bigInteger('con_far_concentracion_id')->unsigned();
            $table->bigInteger('con_far_contenido_id')->unsigned();
            $table->bigInteger('con_far_administra_id')->unsigned();


            $table->string('nombre_generico', 300)->nullable();


            $table->foreign('con_far_envase_id')->references('id')->on('contenido_farmaceutica');
            $table->foreign('con_far_forma_id')->references('id')->on('contenido_farmaceutica');
            $table->foreign('con_far_concentracion_id')->references('id')->on('contenido_farmaceutica');
            $table->foreign('con_far_contenido_id')->references('id')->on('contenido_farmaceutica');
            $table->foreign('con_far_administra_id')->references('id')->on('contenido_farmaceutica');

            $table->foreign('farmacia_articulo_id')->references('id')->on('farmacia_articulo');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articulo_medicamento');
    }
}
