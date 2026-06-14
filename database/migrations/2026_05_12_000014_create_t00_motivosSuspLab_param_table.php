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
        Schema::create('param.t00_motivosSuspLab', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('codigo', 10)->nullable();
            $table->smallInteger('tipoSuspensionLaboral_id')->nullable();
            $table->string('descripcion', 200)->nullable();
            $table->string('abreviatura', 10)->nullable();
            $table->boolean('conGoceHaber')->nullable();
            $table->string('asusfal', 1)->nullable();
            $table->decimal('diasMaxCiclo', 15, 4)->nullable();
            $table->string('codigoProg', 20)->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->boolean('activo')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->foreign('tipoSuspensionLaboral_id')->references('id')->on('param.t21_tipoSuspensionLaboral');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('param.t00_motivosSuspLab');
    }
};
