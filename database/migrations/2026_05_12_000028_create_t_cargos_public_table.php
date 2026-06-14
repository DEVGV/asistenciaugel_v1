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
        Schema::create('t_cargos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo', 20)->nullable();
            $table->string('nombre', 100)->nullable();
            $table->string('abreviatura', 100)->nullable();
            $table->smallInteger('rolTrabajador_id')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->boolean('activo')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->foreign('rolTrabajador_id')->references('id')->on('param.t00_rolTrabajador');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_cargos');
    }
};
