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

            $table->bigInteger('consulta_id')->unsigned(); // saber que # de consulta fue
            $table->bigInteger('paciente_id')->unsigned(); // obtener las recetas de x paciente
            $table->bigInteger('via_id')->unsigned();
            $table->bigInteger('diagnostico_id')->unsigned();
            $table->bigInteger('usuario_id')->unsigned();

            $table->text('descripcion_general')->nullable();
            $table->date('fecha');
            $table->date('proxima_cita')->nullable();

            // estado de entrega de receta

            // 1: pendiente
            // 2: procesada
            // 3: denegada

            $table->integer('estado');

            $table->date('fecha_denegada')->nullable();
            $table->string('nota_denegada', 500)->nullable();

            $table->foreign('consulta_id')->references('id')->on('consulta_paciente');
            $table->foreign('paciente_id')->references('id')->on('paciente');
            $table->foreign('via_id')->references('id')->on('via_receta');
            $table->foreign('diagnostico_id')->references('id')->on('diagnosticos');
            $table->foreign('usuario_id')->references('id')->on('usuario');
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
