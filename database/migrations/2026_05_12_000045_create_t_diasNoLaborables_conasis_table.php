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
        Schema::create('conasis.t_diasNoLaborables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->smallInteger('feriado_id')->nullable();
            $table->date('fecha')->nullable();
            $table->string('observacion', 200)->nullable();
            $table->string('nacionalLocal', 1)->nullable();
            $table->string('recuperable', 1)->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->boolean('activo')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->foreign('feriado_id')->references('id')->on('param.t00_feriados');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conasis.t_diasNoLaborables');
    }
};
