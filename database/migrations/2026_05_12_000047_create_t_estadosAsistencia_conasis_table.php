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
        Schema::create('conasis.t_estadosAsistencia', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('asistencia_id');
            $table->bigInteger('estadoAsis_id');
            $table->string('observacion', 255)->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->foreign('estadoAsis_id')->references('id')->on('param.t00_estadosAsis');
            $table->foreign('asistencia_id')->references('id')->on('conasis.t_asistencia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conasis.t_estadosAsistencia');
    }
};
