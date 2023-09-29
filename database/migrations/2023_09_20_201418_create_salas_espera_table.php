<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalasEsperaTable extends Migration
{
    /**
     * Diferentes tipos de salas de espera
     * Consultorio
     * Enfermeria
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salas_espera', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salas_espera');
    }
}
