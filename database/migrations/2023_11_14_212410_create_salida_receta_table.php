<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalidaRecetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salida_receta', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('recetas_id')->unsigned();
            $table->bigInteger('usuario_id')->unsigned();
            $table->dateTime('fecha');
            $table->text('notas')->nullable();




            $table->foreign('recetas_id')->references('id')->on('recetas');
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
        Schema::dropIfExists('salida_receta');
    }
}
