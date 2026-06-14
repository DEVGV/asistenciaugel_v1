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
        Schema::create('t_historialCambios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('esquema', 20)->nullable();
            $table->string('tabla', 50)->nullable();
            $table->bigInteger('registro_id')->nullable();
            $table->string('accion', 20)->nullable();
            $table->string('dato_old', 300)->nullable();
            $table->string('dato_new', 300)->nullable();
            $table->timestamp('accion_at')->nullable();
            $table->bigInteger('accion_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_historialCambios');
    }
};
