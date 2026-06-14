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
        Schema::create('t_entidades', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->smallInteger('tipoEntidad_id')->nullable();
            $table->string('ruc', 20)->nullable();
            $table->string('razonSocial', 200)->nullable();
            $table->string('razonComercial', 200)->nullable();
            $table->bigInteger('personaRepLegal_id')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->boolean('activo')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->foreign('tipoEntidad_id')->references('id')->on('param.t00_tipoEntidad');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_entidades');
    }
};
