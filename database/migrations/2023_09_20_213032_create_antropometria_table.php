<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAntropometriaTable extends Migration
{
    /**
     * Run the migrations. n
     *
     * @return void
     */
    public function up()
    {
        Schema::create('antropometria', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('consulta_id')->unsigned();
            $table->dateTime('fecha_hora');
            $table->string('frecuencia_cardiaca', 150)->nullable();
            $table->string('frecuencia_respiratoria', 150)->nullable();
            $table->string('presion_arterial', 150)->nullable();
            $table->decimal('temperatura', 8, 2)->nullable();
            $table->decimal('perim_abdominal', 8, 2)->nullable();
            $table->decimal('perim_cefalico', 8, 2)->nullable();
            $table->decimal('peso', 8, 2)->nullable();
            $table->decimal('estatura', 8, 2)->nullable();
            $table->string('glucometria_capilar', 150)->nullable();
            $table->string('cetonas_capilares', 150)->nullable();
            $table->string('spo2', 150)->nullable();
            $table->decimal('perim_cintura', 8, 2)->nullable();
            $table->decimal('perim_cadera', 8, 2)->nullable();
            $table->string('gasto_energetico_basal', 150)->nullable();
            $table->string('nota_adicional', 850)->nullable();



            $table->foreign('consulta_id')->references('id')->on('consulta_paciente');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('antropometria');
    }
}
