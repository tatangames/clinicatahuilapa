<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePacienteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paciente', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tipo_id')->unsigned();
            $table->bigInteger('estado_civil_id')->unsigned();
            $table->bigInteger('tipo_documento_id')->unsigned();
            $table->bigInteger('profesion_id')->unsigned();

            $table->string('nombres', 150)->nullable();
            $table->string('apellidos', 150)->nullable();
            $table->date('fecha_nacimiento');
            $table->char('sexo')->nullable();
            $table->string('referido_por', 300)->nullable();
            $table->string('num_documento', 100);
            $table->string('correo', 150)->nullable();
            $table->string('celular', 25)->nullable();
            $table->string('telefono', 25)->nullable();
            $table->string('direccion', 550)->nullable();
            $table->string('foto', 100)->nullable();

            $table->foreign('tipo_documento_id')->references('id')->on('tipo_documento');
            $table->foreign('tipo_id')->references('id')->on('tipo_paciente');
            $table->foreign('estado_civil_id')->references('id')->on('estado_civil');
            $table->foreign('profesion_id')->references('id')->on('profesion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paciente');
    }
}
