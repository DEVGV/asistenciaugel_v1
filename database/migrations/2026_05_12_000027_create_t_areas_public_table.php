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
        Schema::create('t_areas', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('codigo', 20)->nullable();
            $table->string('nombre', 100)->nullable();
            $table->string('sigla', 20)->nullable();
            $table->string('descripcion', 100)->nullable();
            $table->smallInteger('rolTrabajador_id')->nullable();
            $table->boolean('activo')->nullable();
            $table->foreign('rolTrabajador_id')->references('id')->on('param.t00_rolTrabajador');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_areas');
    }
};
