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
        Schema::create('conasis.t_consolAsistMesTrab', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('asistencia_id')->nullable();
            $table->smallInteger('motivoSuspLab_id')->nullable();
            $table->string('sigla', 5)->nullable();
            $table->decimal('ndias', 15, 4)->nullable();
            $table->boolean('remunerado')->nullable();
            $table->bigInteger('localInstEduc_id')->nullable();
            $table->string('asusfal', 1)->nullable();
            $table->smallInteger('aniopla')->nullable();
            $table->smallInteger('mespla')->nullable();
            $table->decimal('ndiaspla', 15, 4)->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->timestamp('createdenv_at')->nullable();
            $table->bigInteger('createdenv_by')->nullable();
            $table->smallInteger('estadoUltim_id')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->foreign('motivoSuspLab_id')->references('id')->on('param.t00_motivosSuspLab');
            $table->foreign('asistencia_id')->references('id')->on('conasis.t_asistencia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conasis.t_consolAsistMesTrab');
    }
};
