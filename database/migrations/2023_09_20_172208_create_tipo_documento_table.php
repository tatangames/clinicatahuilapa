<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipoDocumentoTable extends Migration
{
    /**
     * DUI, nit, pasaporte
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_documento', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipo_documento');
    }
}
