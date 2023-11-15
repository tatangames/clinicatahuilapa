<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsultaPacienteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consulta_paciente', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('paciente_id')->unsigned();
            $table->bigInteger('motivo_id')->unsigned();

            // PARA VER A QUE SALA FUE ASIGNADO EL PACIENTE
            $table->bigInteger('salaespera_id')->unsigned();
            $table->dateTime('fecha_hora');

            // * Solo puede haber 1 usuario dentro de una SALA

            // 0: Paciente esperando ser ingresado a la Sala, porque estamos en espera
            // 1: Paciente esta dentro de la sala actualmente a la que fue asignado
            // 2: Paciente fue despachado

            $table->integer('estado_paciente');


            // hora ingreso dentro de una sala, si es movido de nuevo a cola, se resetea a null
            $table->dateTime('hora_dentrosala')->nullable();


            // 0: no tiene receta asignada
            // 1: tiene receta asignada
            // 2: paciente retiro receta

            $table->integer('estado_receta')->nullable();


            $table->foreign('paciente_id')->references('id')->on('paciente');
            $table->foreign('motivo_id')->references('id')->on('motivo');
            $table->foreign('salaespera_id')->references('id')->on('salas_espera');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consulta_paciente');
    }
}
