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
        Schema::create('t_emails', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('persona_id');
            $table->bigInteger('entidad_id')->nullable();
            $table->bigInteger('institucionEduc_id')->nullable();
            $table->string('email', 200)->nullable();
            $table->string('personalInst', 1)->nullable();
            $table->date('fechaInicio')->nullable();
            $table->date('fechaFin')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->foreign('entidad_id')->references('id')->on('t_entidades');
            $table->foreign('institucionEduc_id')->references('id')->on('t_institucionesEduc');
            $table->foreign('persona_id')->references('id')->on('t_personas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_emails');
    }
};
