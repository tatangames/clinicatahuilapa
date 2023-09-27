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
            $table->dateTime('fecha_hora');


            // 1: se creo la consulta y pasa a sala de espera

            $table->integer('estado_paciente');


            // 0: no tiene receta asignada
            // 1: tiene receta asignada
            // 2: paciente retiro receta

            $table->integer('estado_receta')->nullable();


            $table->foreign('paciente_id')->references('id')->on('paciente');
            $table->foreign('motivo_id')->references('id')->on('motivo');
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
