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
            $table->bigInteger('usuario_id')->unsigned(); // quien lleno esta hoja

            $table->dateTime('fecha');
            $table->time('hora');
            $table->string('frecuencia_cardiaca', 150)->nullable();
            $table->string('frecuencia_respiratoria', 150)->nullable();
            $table->string('presion_arterial', 150)->nullable();
            $table->string('temperatura', 150)->nullable();
            $table->string('perim_abdominal', 150)->nullable();
            $table->string('perim_cefalico', 150)->nullable();
            $table->string('peso_libra', 150)->nullable();
            $table->string('peso_kilo', 150)->nullable();
            $table->string('estatura', 150)->nullable();
            $table->string('imc', 150)->nullable();
            $table->string('resultado_imc', 150)->nullable();
            $table->string('glucometria_capilar', 150)->nullable();
            $table->string('glicohemoglibona_capilar', 150)->nullable();
            $table->string('cetona_capilar', 150)->nullable();
            $table->string('spo2', 150)->nullable();
            $table->string('perim_cintura', 150)->nullable();
            $table->string('perim_cadera', 150)->nullable();

            $table->string('icc', 150)->nullable();
            $table->string('riesgo_mujer', 150)->nullable();
            $table->string('riesgo_hombre', 150)->nullable();
            $table->string('gasto_energetico_basal', 150)->nullable();
            $table->text('nota_adicional')->nullable();


            $table->foreign('consulta_id')->references('id')->on('consulta_paciente');
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
        Schema::dropIfExists('antropometria');
    }
}
