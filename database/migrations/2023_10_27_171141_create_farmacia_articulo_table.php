<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmaciaArticuloTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farmacia_articulo', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('linea_id')->unsigned();
            $table->bigInteger('sublinea_id')->unsigned();

            $table->string('nombre', 300);
            $table->string('codigo_articulo', 300)->nullable();

            $table->integer('existencia_minima')->nullable();


            $table->foreign('linea_id')->references('id')->on('linea');
            $table->foreign('sublinea_id')->references('id')->on('sub_linea');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('farmacia_articulo');
    }
}
