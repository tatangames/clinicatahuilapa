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
            $table->bigInteger('medicamento_id')->unsigned();

            $table->integer('cantidad'); // es lo que se retira
            $table->text('descripcion')->nullable();


            $table->foreign('recetas_id')->references('id')->on('recetas');
            $table->foreign('medicamento_id')->references('id')->on('farmacia_articulo');
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
