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
        Schema::create('conasis.t_controladoresLocales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('localInstEduc_id');
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('trabajador_id')->nullable();
            $table->bigInteger('altaTrabajador_id')->nullable();
            $table->date('fechaInicio')->nullable();
            $table->date('fechaFin')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->foreign('altaTrabajador_id')->references('id')->on('t_altasTrabajadores');
            $table->foreign('localInstEduc_id')->references('id')->on('conasis.t_localesInstEduc');
            $table->foreign('trabajador_id')->references('id')->on('t_trabajador');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conasis.t_controladoresLocales');
    }
};
