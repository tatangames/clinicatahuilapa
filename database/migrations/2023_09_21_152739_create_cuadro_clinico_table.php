<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuadroClinicoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuadro_clinico', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('consulta_id')->unsigned();
            $table->bigInteger('diagnostico_id')->unsigned();
            $table->text('descripcion');

            $table->foreign('consulta_id')->references('id')->on('consulta_paciente');
            $table->foreign('diagnostico_id')->references('id')->on('diagnosticos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cuadro_clinico');
    }
}
