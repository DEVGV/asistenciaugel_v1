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
        Schema::create('t_zonas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->smallInteger('tipoZona_id');
            $table->smallInteger('distrito_id')->nullable();
            $table->string('nombre', 100)->nullable();
            $table->string('abreviatura', 50)->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->boolean('activo')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->foreign('distrito_id')->references('id')->on('param.t28_distritos');
            $table->foreign('tipoZona_id')->references('id')->on('param.t6_tiposZona');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_zonas');
    }
};
