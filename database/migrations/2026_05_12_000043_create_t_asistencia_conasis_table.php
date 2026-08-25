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
        Schema::create('conasis.t_asistencia', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('institucionEduc_id');
            $table->bigInteger('trabajador_id');
            $table->bigInteger('altaTrabajador_id');
            $table->smallInteger('anio');
            $table->smallInteger('mes');
            $table->decimal('ndias_asis', 15, 4)->nullable();
            $table->decimal('nhoras_crono', 15, 4)->nullable();
            $table->decimal('nhoras_acad', 15, 4)->nullable();
            $table->decimal('ndias_perm', 15, 4)->nullable();
            $table->decimal('nhoras_perm', 15, 4)->nullable();
            $table->decimal('nminu_perm', 15, 4)->nullable();
            $table->decimal('ndias_falt', 15, 4)->nullable();
            $table->decimal('ndias_tarde', 15, 4)->nullable();
            $table->decimal('nhoras_tarde', 15, 4)->nullable();
            $table->decimal('nminu_tarde', 15, 4)->nullable();
            $table->decimal('ndias_extra', 15, 4)->nullable();
            $table->decimal('nhoras_extra', 15, 4)->nullable();
            $table->decimal('nnimu_extra', 15, 4)->nullable();
            $table->date('fechaDesde')->nullable();
            $table->date('fechaHasta')->nullable();
            $table->smallInteger('estadoUltim_id')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->foreign('estadoUltim_id')->references('id')->on('param.t00_estadosAsis');
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
        Schema::dropIfExists('conasis.t_asistencia');
    }
};
