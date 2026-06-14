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
        Schema::create('conasis.t_horariosTrabajador', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('codigo', 30)->nullable();
            $table->smallInteger('anio')->nullable();
            $table->bigInteger('institucionEduc_id')->nullable();
            $table->bigInteger('trabajador_id');
            $table->bigInteger('altaTrabajador_id')->nullable();
            $table->string('tipoHorario', 1)->nullable();
            $table->string('nombre', 100)->nullable();
            $table->date('fechaInicio')->nullable();
            $table->date('fechaFin')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->boolean('archivado')->nullable();
            $table->boolean('activo')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->foreign('altaTrabajador_id')->references('id')->on('t_altasTrabajadores');
            $table->foreign('institucionEduc_id')->references('id')->on('t_institucionesEduc');
            $table->foreign('trabajador_id')->references('id')->on('t_trabajador');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conasis.t_horariosTrabajador');
    }
};
