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
        Schema::create('conasis.t_cargaHoraria', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('horarioCurso_id');
            $table->bigInteger('trabajador_id');
            $table->bigInteger('altaTrabajador_id')->nullable();
            $table->date('fechaInicio')->nullable();
            $table->date('fechaFin')->nullable();
            $table->string('titularSuplencia', 1)->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->foreign('altaTrabajador_id')->references('id')->on('t_altasTrabajadores');
            $table->foreign('horarioCurso_id')->references('id')->on('conasis.t_horariosCursos');
            $table->foreign('trabajador_id')->references('id')->on('t_trabajador');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conasis.t_cargaHoraria');
    }
};
