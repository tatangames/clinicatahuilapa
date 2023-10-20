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


            $table->text('antecedentes_familiares')->nullable();
            $table->text('alergias')->nullable();
            $table->text('medicamentos_actuales')->nullable();
            $table->string('tipeo_sangre', 25)->nullable();
            $table->text('complicaciones_diabetes')->nullable();
            $table->text('enfermedades_cronicas')->nullable();
            $table->text('antecedentes_quirurgicos')->nullable();
            $table->text('antecedentes_oftalmologicos')->nullable();
            $table->text('antecedentes_deportivos')->nullable();
            $table->text('antecedentes_ginecologicos')->nullable();
            $table->text('otros')->nullable();

            $table->foreign('paciente_id')->references('id')->on('paciente');
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
