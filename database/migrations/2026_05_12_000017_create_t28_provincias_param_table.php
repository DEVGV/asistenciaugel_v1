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
        Schema::create('param.t28_provincias', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('codigo', 20)->nullable();
            $table->string('nombre', 100)->nullable();
            $table->string('abreviatura', 100)->nullable();
            $table->smallInteger('departamento_id');
            $table->boolean('activo')->nullable();
            $table->foreign('departamento_id')->references('id')->on('param.t28_departamento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('param.t28_provincias');
    }
};
