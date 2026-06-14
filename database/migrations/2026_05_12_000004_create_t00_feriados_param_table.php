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
        Schema::create('param.t00_feriados', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('codigo', 20)->nullable();
            $table->string('descripcion', 100)->nullable();
            $table->string('diaMesDefault', 20)->nullable();
            $table->boolean('programaDefault')->nullable();
            $table->boolean('activo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('param.t00_feriados');
    }
};
