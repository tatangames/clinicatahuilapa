<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecetasDetalleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recetas_detalle', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('recetas_id')->unsigned();
            $table->bigInteger('entrada_detalle_id')->unsigned();
            $table->bigInteger('via_id')->unsigned();

            $table->integer('cantidad'); // es lo que se retira
            $table->text('descripcion')->nullable();

            $table->foreign('via_id')->references('id')->on('via_receta');
            $table->foreign('recetas_id')->references('id')->on('recetas');
            $table->foreign('entrada_detalle_id')->references('id')->on('entrada_medicamento_detalle');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recetas_detalle');
    }
}
