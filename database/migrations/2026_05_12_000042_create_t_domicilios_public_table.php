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
        Schema::create('t_domicilios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('persona_id')->nullable();
            $table->bigInteger('entidad_id')->nullable();
            $table->bigInteger('institucionEduc_id')->nullable();
            $table->string('domicilio', 200)->nullable();
            $table->bigInteger('zona_id');
            $table->string('ubigeo', 20)->nullable();
            $table->date('fechaInicio')->nullable();
            $table->date('fechaFin')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->foreign('entidad_id')->references('id')->on('t_entidades');
            $table->foreign('institucionEduc_id')->references('id')->on('t_institucionesEduc');
            $table->foreign('persona_id')->references('id')->on('t_personas');
            $table->foreign('zona_id')->references('id')->on('t_zonas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_domicilios');
    }
};
