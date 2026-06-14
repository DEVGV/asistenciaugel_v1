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
        Schema::create('conasis.t_expediente', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('codigo', 30)->nullable();
            $table->smallInteger('anio')->nullable();
            $table->bigInteger('trabajador_id')->nullable();
            $table->string('asunto', 255)->nullable();
            $table->date('fecha')->nullable();
            $table->string('observacion', 255)->nullable();
            $table->smallInteger('estado_id')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->foreign('estado_id')->references('id')->on('param.t00_estadosTram');
            $table->foreign('trabajador_id')->references('id')->on('t_trabajador');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conasis.t_expediente');
    }
};
