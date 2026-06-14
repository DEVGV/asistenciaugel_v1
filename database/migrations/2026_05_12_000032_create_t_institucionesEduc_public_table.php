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
        Schema::create('t_institucionesEduc', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('entidadUgel_id')->nullable();
            $table->bigInteger('entidadAdmin_id')->nullable();
            $table->string('codigoInstitucion', 30)->nullable();
            $table->string('codigoModular', 30)->nullable();
            $table->string('nombreLegal', 200)->nullable();
            $table->smallInteger('regimenEduc_id')->nullable();
            $table->smallInteger('tipoInstEduc_id')->nullable();
            $table->smallInteger('modalidadFormativa_id');
            $table->smallInteger('nivelCiclo_id');
            $table->date('fechaInicio')->nullable();
            $table->date('fechaFin')->nullable();
            $table->smallInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->foreign('modalidadFormativa_id')->references('id')->on('param.t34_modalidadesForm');
            $table->foreign('nivelCiclo_id')->references('id')->on('param.t34_nivelesCiclo');
            $table->foreign('regimenEduc_id')->references('id')->on('param.t34_regimenEduc');
            $table->foreign('tipoInstEduc_id')->references('id')->on('param.t34_tipoInstEduc');
            $table->foreign('entidadUgel_id')->references('id')->on('t_entidades');
            $table->foreign('entidadAdmin_id')->references('id')->on('t_entidades');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_institucionesEduc');
    }
};
