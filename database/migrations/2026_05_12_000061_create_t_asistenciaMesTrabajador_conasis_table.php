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
        Schema::create('conasis.t_asistenciaMesTrabajador', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('asistencia_id');
            $table->bigInteger('localInstEduc_id')->nullable();
            $table->smallInteger('turno_id')->nullable();
            $table->smallInteger('nroTurno')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->string('e1', 30)->nullable();
            $table->string('s1', 30)->nullable();
            $table->string('c1', 10)->nullable();
            $table->string('e2', 30)->nullable();
            $table->string('s2', 30)->nullable();
            $table->string('c2', 10)->nullable();
            $table->string('e3', 30)->nullable();
            $table->string('s3', 30)->nullable();
            $table->string('c3', 10)->nullable();
            $table->string('e4', 30)->nullable();
            $table->string('s4', 30)->nullable();
            $table->string('c4', 10)->nullable();
            $table->string('e5', 30)->nullable();
            $table->string('s5', 30)->nullable();
            $table->string('c5', 10)->nullable();
            $table->string('e6', 30)->nullable();
            $table->string('s6', 30)->nullable();
            $table->string('c6', 10)->nullable();
            $table->string('e7', 30)->nullable();
            $table->string('s7', 30)->nullable();
            $table->string('c7', 10)->nullable();
            $table->string('e8', 30)->nullable();
            $table->string('s8', 30)->nullable();
            $table->string('c8', 10)->nullable();
            $table->string('e9', 30)->nullable();
            $table->string('s9', 30)->nullable();
            $table->string('c9', 10)->nullable();
            $table->string('e10', 30)->nullable();
            $table->string('s10', 30)->nullable();
            $table->string('c10', 10)->nullable();
            $table->string('e11', 30)->nullable();
            $table->string('s11', 30)->nullable();
            $table->string('c11', 10)->nullable();
            $table->string('e12', 30)->nullable();
            $table->string('s12', 30)->nullable();
            $table->string('c12', 10)->nullable();
            $table->string('e13', 30)->nullable();
            $table->string('s13', 30)->nullable();
            $table->string('c13', 10)->nullable();
            $table->string('e14', 30)->nullable();
            $table->string('s14', 30)->nullable();
            $table->string('c14', 10)->nullable();
            $table->string('e15', 30)->nullable();
            $table->string('s15', 30)->nullable();
            $table->string('c15', 10)->nullable();
            $table->string('e16', 30)->nullable();
            $table->string('s16', 30)->nullable();
            $table->string('c16', 10)->nullable();
            $table->string('e17', 30)->nullable();
            $table->string('s17', 30)->nullable();
            $table->string('c17', 10)->nullable();
            $table->string('e18', 30)->nullable();
            $table->string('s18', 30)->nullable();
            $table->string('c18', 10)->nullable();
            $table->string('e19', 30)->nullable();
            $table->string('s19', 30)->nullable();
            $table->string('c19', 10)->nullable();
            $table->string('e20', 30)->nullable();
            $table->string('s20', 30)->nullable();
            $table->string('c20', 10)->nullable();
            $table->string('e21', 30)->nullable();
            $table->string('s21', 30)->nullable();
            $table->string('c21', 10)->nullable();
            $table->string('e22', 30)->nullable();
            $table->string('s22', 30)->nullable();
            $table->string('c22', 10)->nullable();
            $table->string('e23', 30)->nullable();
            $table->string('s23', 30)->nullable();
            $table->string('c23', 10)->nullable();
            $table->string('e24', 30)->nullable();
            $table->string('s24', 30)->nullable();
            $table->string('c24', 10)->nullable();
            $table->string('e25', 30)->nullable();
            $table->string('s25', 30)->nullable();
            $table->string('c25', 10)->nullable();
            $table->string('e26', 30)->nullable();
            $table->string('s26', 30)->nullable();
            $table->string('c26', 10)->nullable();
            $table->string('e27', 30)->nullable();
            $table->string('s27', 30)->nullable();
            $table->string('c27', 10)->nullable();
            $table->string('e28', 30)->nullable();
            $table->string('s28', 30)->nullable();
            $table->string('c28', 10)->nullable();
            $table->string('e29', 30)->nullable();
            $table->string('s29', 30)->nullable();
            $table->string('c29', 10)->nullable();
            $table->string('e30', 30)->nullable();
            $table->string('s30', 30)->nullable();
            $table->string('c30', 10)->nullable();
            $table->string('e31', 30)->nullable();
            $table->string('s31', 30)->nullable();
            $table->string('c31', 10)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->foreign('turno_id')->references('id')->on('param.t00_turnos');
            $table->foreign('asistencia_id')->references('id')->on('conasis.t_asistencia');
            $table->foreign('localInstEduc_id')->references('id')->on('conasis.t_localesInstEduc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conasis.t_asistenciaMesTrabajador');
    }
};
