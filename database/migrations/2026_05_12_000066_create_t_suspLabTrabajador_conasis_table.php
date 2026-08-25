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
        Schema::create('conasis.t_suspLabTrabajador', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('trabajador_id');
            $table->bigInteger('altaTrabajador_id')->nullable();
            $table->smallInteger('motivoSuspLab_id');
            $table->timestamp('fechaHoraInicio')->nullable();
            $table->timestamp('fechaHoraFin')->nullable();
            $table->timestamp('fechaRegistro')->nullable();
            $table->decimal('totalDias', 15, 4)->nullable();
            $table->decimal('totalHoras', 15, 4)->nullable();
            $table->string('marcaApli', 2)->nullable();
            $table->string('observacion', 255)->nullable();
            $table->smallInteger('turno')->nullable();
            $table->bigInteger('expediente_id')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->foreign('motivoSuspLab_id')->references('id')->on('param.t00_motivosSuspLab');
            $table->foreign('altaTrabajador_id')->references('id')->on('t_altasTrabajadores');
            $table->foreign('expediente_id')->references('id')->on('conasis.t_expediente');
            $table->foreign('trabajador_id')->references('id')->on('t_trabajador');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conasis.t_suspLabTrabajador');
    }
};
