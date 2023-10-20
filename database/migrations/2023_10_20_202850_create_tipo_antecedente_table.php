<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipoAntecedenteTable extends Migration
{
    /**
     * TIPOS DE ANTECEDENTES
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_antecedente', function (Blueprint $table) {
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
        Schema::dropIfExists('tipo_antecedente');
    }
}
