<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePacienteAntecedentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paciente_antecedentes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('paciente_id')->unsigned();
            $table->bigInteger('antecedente_medico_id')->unsigned();

            $table->foreign('paciente_id')->references('id')->on('paciente');
            $table->foreign('antecedente_medico_id')->references('id')->on('antecedentes_medicos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paciente_antecedentes');
    }
}
