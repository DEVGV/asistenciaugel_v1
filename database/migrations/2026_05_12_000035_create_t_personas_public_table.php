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
        Schema::create('t_personas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->smallInteger('pais_id');
            $table->smallInteger('tipoDocIdentidad_id');
            $table->string('docIdentidad', 20)->nullable();
            $table->string('paterno', 100);
            $table->string('materno', 100);
            $table->string('nombre', 150)->nullable();
            $table->smallInteger('sexo_id');
            $table->date('fechaNac')->nullable();
            $table->string('ubigeo', 20)->nullable();
            $table->string('foto', 200)->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->boolean('activo')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->foreign('sexo_id')->references('id')->on('param.t00_sexos');
            $table->foreign('tipoDocIdentidad_id')->references('id')->on('param.t3_tipoDocIdentidad');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_personas');
    }
};
