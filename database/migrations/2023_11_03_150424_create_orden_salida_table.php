<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdenSalidaTable extends Migration
{
    /**
     * ESTAS SON SALIDAS MANUALES,
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orden_salida', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('usuario_id')->unsigned();
            $table->bigInteger('motivo_id')->unsigned();

            $table->date('fecha'); // elegida en vista
            $table->time('hora'); // colocada automaticamente
            $table->text('observaciones')->nullable();


            $table->foreign('usuario_id')->references('id')->on('usuario');
            $table->foreign('motivo_id')->references('id')->on('motivo_farmacia');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orden_salida');
    }
}
