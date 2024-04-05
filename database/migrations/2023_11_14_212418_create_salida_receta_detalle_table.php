<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalidaRecetaDetalleTable extends Migration
{
    /**
     * DETALLE DE LA SALIDA DE MEDICAMENTO DE LA RECETA
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salida_receta_detalle', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('salidareceta_id')->unsigned();
            $table->bigInteger('entrada_detalle_id')->unsigned();


            $table->integer('cantidad');


            $table->foreign('salidareceta_id')->references('id')->on('salida_receta');
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
        Schema::dropIfExists('salida_receta_detalle');
    }
}
