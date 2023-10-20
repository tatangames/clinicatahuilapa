<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAntecedentesMedicosTable extends Migration
{
    /**
     * LISTADO DE ANTECEDENTES MEDICOS POR TIPO
     *
     * @return void
     */
    public function up()
    {
        Schema::create('antecedentes_medicos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tipo_id')->unsigned();
            $table->string('nombre', 200);

            $table->foreign('tipo_id')->references('id')->on('tipo_antecedente');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('antecedentes_medicos');
    }
}
