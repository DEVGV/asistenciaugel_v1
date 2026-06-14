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
        Schema::create('conasis.t_localesInstEduc', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('local_id')->nullable();
            $table->bigInteger('entidad_id')->nullable();
            $table->bigInteger('institucionEduc_id')->nullable();
            $table->date('fechaInicio')->nullable();
            $table->date('fechaFin')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->foreign('entidad_id')->references('id')->on('t_entidades');
            $table->foreign('institucionEduc_id')->references('id')->on('t_institucionesEduc');
            $table->foreign('local_id')->references('id')->on('conasis.t_locales');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conasis.t_localesInstEduc');
    }
};
