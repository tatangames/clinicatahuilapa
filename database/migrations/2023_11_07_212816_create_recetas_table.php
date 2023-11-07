<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecetasTable extends Migration
{
    /**
     * REGISTRO DE RECETAS POR CONSULTA MEDICA
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recetas', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('consulta_id')->unsigned();
            $table->bigInteger('fuente_finan_id')->unsigned();
            $table->bigInteger('via_id')->unsigned();

            $table->text('descripcion_general')->nullable();
            $table->date('fecha');
            $table->date('proxima_cita')->nullable();


            $table->foreign('consulta_id')->references('id')->on('consulta_paciente');
            $table->foreign('fuente_finan_id')->references('id')->on('fuente_financiamiento');
            $table->foreign('via_id')->references('id')->on('via_receta');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recetas');
    }
}
