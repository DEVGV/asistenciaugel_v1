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
        Schema::create('conasis.t_documentosTram', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('expediente_id')->nullable();
            $table->bigInteger('documento_id')->nullable();
            $table->string('nroDoc', 150)->nullable();
            $table->date('fechaDoc')->nullable();
            $table->bigInteger('trabajadorDoc_id')->nullable();
            $table->string('rutaDoc', 255)->nullable();
            $table->string('observacion', 255)->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->foreign('documento_id')->references('id')->on('param.t00_documentos');
            $table->foreign('expediente_id')->references('id')->on('conasis.t_expediente');
            $table->foreign('trabajadorDoc_id')->references('id')->on('t_trabajador');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conasis.t_documentosTram');
    }
};
