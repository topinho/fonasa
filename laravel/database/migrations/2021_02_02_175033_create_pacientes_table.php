<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePacientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->integer('edad');
            $table->enum('tipo_paciente', ['Nino', 'Joven', 'Anciano']);
            $table->integer('numero_historia_clinica');
            $table->integer('prioridad');
            $table->float('riesgo');
            $table->enum('estado', ['Pendiente', 'En Espera', 'En Atencion', 'Atendido']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pacientes');
    }
}
