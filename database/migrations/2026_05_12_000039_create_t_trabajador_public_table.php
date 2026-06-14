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
        Schema::create('t_trabajador', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('codigo', 30)->nullable();
            $table->bigInteger('persona_id')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->boolean('activo')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->foreign('persona_id')->references('id')->on('t_personas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_trabajador');
    }
};
