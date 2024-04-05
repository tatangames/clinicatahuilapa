<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdenSalidaDetalleTable extends Migration
{
    /**
     * DETALLE DE SALIDAS MANUALES
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orden_salida_detalle', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('orden_salida_id')->unsigned();
            $table->bigInteger('entrada_medi_detalle_id')->unsigned();

            $table->integer('cantidad'); // es lo que se retira

            $table->foreign('orden_salida_id')->references('id')->on('orden_salida');
            $table->foreign('entrada_medi_detalle_id')->references('id')->on('entrada_medicamento_detalle');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orden_salida_detalle');
    }
}
