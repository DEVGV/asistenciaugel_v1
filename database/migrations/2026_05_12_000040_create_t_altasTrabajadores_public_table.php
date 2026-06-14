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
        Schema::create('t_altasTrabajadores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('codigoAirsp', 20)->nullable();
            $table->bigInteger('trabajador_id');
            $table->date('fechaInicio');
            $table->date('fechaFin')->nullable();
            $table->date('fechaAlta');
            $table->smallInteger('condicionLaboral_id');
            $table->smallInteger('tipoContrato_id');
            $table->bigInteger('institucionEducativa_id');
            $table->smallInteger('rolTrabajador_id')->nullable();
            $table->smallInteger('area_id');
            $table->integer('cargo_id');
            $table->smallInteger('situacionLaboral_id');
            $table->string('observacion', 200)->nullable();
            $table->date('fechaBaja')->nullable();
            $table->smallInteger('motivoBaja_id')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->foreign('rolTrabajador_id')->references('id')->on('param.t00_rolTrabajador');
            $table->foreign('tipoContrato_id')->references('id')->on('param.t12_tipoContrato');
            $table->foreign('situacionLaboral_id')->references('id')->on('param.t15_situacionLaboral');
            $table->foreign('motivoBaja_id')->references('id')->on('param.t17_motivoDeBajas');
            $table->foreign('area_id')->references('id')->on('t_areas');
            $table->foreign('cargo_id')->references('id')->on('t_cargos');
            $table->foreign('condicionLaboral_id')->references('id')->on('t_condicionesLaborales');
            $table->foreign('institucionEducativa_id')->references('id')->on('t_institucionesEduc');
            $table->foreign('trabajador_id')->references('id')->on('t_trabajador');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_altasTrabajadores');
    }
};
