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
        Schema::create('t_condicionesLaborales', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo', 20)->nullable();
            $table->smallInteger('regimenLaboral_id');
            $table->smallInteger('tipoTrabajador_id');
            $table->string('nombre', 100)->nullable();
            $table->string('abreviatura', 100)->nullable();
            $table->string('descripcion', 200)->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->foreign('regimenLaboral_id')->references('id')->on('param.t33_regimenLaboral');
            $table->foreign('tipoTrabajador_id')->references('id')->on('param.t8_tipoTrabajador');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_condicionesLaborales');
    }
};
