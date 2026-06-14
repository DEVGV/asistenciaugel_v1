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
        Schema::create('conasis.t_detalleHorarios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('horarioTrabajador_id');
            $table->smallInteger('turno_id')->nullable();
            $table->string('nombreTurno', 100)->nullable();
            $table->smallInteger('nroTurno')->nullable();
            $table->string('diaSemana', 1)->nullable();
            $table->smallInteger('nroDia')->nullable();
            $table->bigInteger('horarioCursoIni_id')->nullable();
            $table->smallInteger('entDiaInicio')->nullable();
            $table->smallInteger('entDiaFin')->nullable();
            $table->time('entHoraInicio')->nullable();
            $table->time('entHoraFin')->nullable();
            $table->smallInteger('entTolerancia')->nullable();
            $table->bigInteger('horarioCursoFin_id')->nullable();
            $table->smallInteger('salDiaInicio')->nullable();
            $table->smallInteger('salDiaFin')->nullable();
            $table->time('salHoraInicio')->nullable();
            $table->time('salHoraFin')->nullable();
            $table->smallInteger('salTolerancia')->nullable();
            $table->decimal('horaAcumula', 15, 4)->nullable();
            $table->boolean('aplicar')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->foreign('turno_id')->references('id')->on('param.t00_turnos');
            $table->foreign('horarioCursoIni_id')->references('id')->on('conasis.t_horariosCursos');
            $table->foreign('horarioTrabajador_id')->references('id')->on('conasis.t_horariosTrabajador');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conasis.t_detalleHorarios');
    }
};
