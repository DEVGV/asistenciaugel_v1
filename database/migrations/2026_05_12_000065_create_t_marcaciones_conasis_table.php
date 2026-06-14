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
        Schema::create('conasis.t_marcaciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('trabajador_id');
            $table->bigInteger('altaTrabajador_id')->nullable();
            $table->bigInteger('localMarcacion_id')->nullable();
            $table->string('codigo', 20)->nullable();
            $table->timestamp('fechaMarcacion')->nullable();
            $table->timestamp('fechaRegistro')->nullable();
            $table->bigInteger('reloj_id')->nullable();
            $table->string('tipo', 1)->nullable();
            $table->string('medioMarcacion', 1)->nullable();
            $table->boolean('procesado')->nullable();
            $table->bigInteger('dispositivoMarca_id')->nullable();
            $table->decimal('utm_huso', 15, 4)->nullable();
            $table->string('utm_base', 10)->nullable();
            $table->decimal('utm_x_este', 15, 4)->nullable();
            $table->decimal('utm_y_norte', 15, 4)->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->foreign('altaTrabajador_id')->references('id')->on('t_altasTrabajadores');
            $table->foreign('dispositivoMarca_id')->references('id')->on('conasis.t_dispositivosMarca');
            $table->foreign('localMarcacion_id')->references('id')->on('conasis.t_localesMarcacion');
            $table->foreign('reloj_id')->references('id')->on('conasis.t_relojes');
            $table->foreign('trabajador_id')->references('id')->on('t_trabajador');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conasis.t_marcaciones');
    }
};
