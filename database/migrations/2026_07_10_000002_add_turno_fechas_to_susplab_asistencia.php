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
        Schema::table('conasis.t_suspLabTrabajador', function (Blueprint $table) {
            $table->smallInteger('turno')->nullable();
        });

        Schema::table('conasis.t_asistencia', function (Blueprint $table) {
            $table->date('fechaDesde')->nullable();
            $table->date('fechaHasta')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conasis.t_suspLabTrabajador', function (Blueprint $table) {
            $table->dropColumn('turno');
        });

        Schema::table('conasis.t_asistencia', function (Blueprint $table) {
            $table->dropColumn(['fechaDesde', 'fechaHasta']);
        });
    }
};
