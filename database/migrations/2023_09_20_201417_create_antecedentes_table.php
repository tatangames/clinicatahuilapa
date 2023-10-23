<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAntecedentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('antecedentes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('paciente_id')->unsigned();
            $table->bigInteger('tipeo_sanguineo_id')->unsigned()->nullable();



            $table->text('antecedentes_familiares')->nullable();
            $table->text('alergias')->nullable();
            $table->text('medicamentos_actuales')->nullable();
            $table->text('nota_antecedente_medico')->nullable();
            $table->text('nota_complicaciones_diabetes')->nullable();
            $table->text('nota_enfermedades_cronicas')->nullable();
            $table->text('nota_antecedentes_quirurgicos')->nullable();
            $table->text('antecedentes_oftalmologicos')->nullable();
            $table->text('antecedentes_deportivos')->nullable();


            $table->string('menarquia', 300)->nullable();
            $table->string('ciclo_menstrual', 300)->nullable();
            $table->string('pap', 300)->nullable();
            $table->string('mamografia', 300)->nullable();


            $table->text('otros')->nullable();


            $table->foreign('paciente_id')->references('id')->on('paciente');
            $table->foreign('tipeo_sanguineo_id')->references('id')->on('tipeo_sanguineo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('antecedentes');
    }
}
