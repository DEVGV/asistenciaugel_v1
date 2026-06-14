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
        Schema::create('t_telefonos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('persona_id')->nullable();
            $table->bigInteger('entidad_id')->nullable();
            $table->bigInteger('institucionEduc_id')->nullable();
            $table->smallInteger('operador_id');
            $table->string('movilFijo', 1)->nullable();
            $table->string('codigoPais', 10)->nullable();
            $table->string('numero', 30)->nullable();
            $table->string('imei', 50)->nullable();
            $table->date('fechaInicio')->nullable();
            $table->date('fechaFin')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->foreign('operador_id')->references('id')->on('param.t00_operadores');
            $table->foreign('entidad_id')->references('id')->on('t_entidades');
            $table->foreign('institucionEduc_id')->references('id')->on('t_institucionesEduc');
            $table->foreign('persona_id')->references('id')->on('t_personas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_telefonos');
    }
};
