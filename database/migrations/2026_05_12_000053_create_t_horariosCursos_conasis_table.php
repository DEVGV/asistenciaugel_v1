<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('conasis.t_horariosCursos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->smallInteger('anio')->nullable();
            $table->bigInteger('seccion_id')->nullable();
            $table->bigInteger('curso_id')->nullable();
            $table->string('diaSemana', 1)->nullable();
            $table->smallInteger('nroDia')->nullable();
            $table->time('horaInicio')->nullable();
            $table->time('horaFin')->nullable();
            $table->decimal('minAcum', 15, 4)->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->foreign('curso_id')->references('id')->on('t_cursosIE');
            $table->foreign('seccion_id')->references('id')->on('t_seccionesIE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conasis.t_horariosCursos');
    }
};
