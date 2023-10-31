<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntradaMedicamentoTable extends Migration
{
    /**
     * REGISTRO DE MEDICAMENTO NUEVO
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entrada_medicamento', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('tipofactura_id')->unsigned();
            $table->bigInteger('fuentefina_id')->unsigned();
            $table->bigInteger('proveedor_id')->unsigned();
            $table->bigInteger('usuario_id')->unsigned();

            $table->dateTime('fecha');
            $table->string('numero_factura', 100);

            $table->foreign('tipofactura_id')->references('id')->on('tipo_factura');
            $table->foreign('fuentefina_id')->references('id')->on('fuente_financiamiento');
            $table->foreign('proveedor_id')->references('id')->on('proveedores');
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
        Schema::dropIfExists('entrada_medicamento');
    }
}
