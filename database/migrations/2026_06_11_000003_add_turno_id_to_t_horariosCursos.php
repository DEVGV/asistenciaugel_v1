<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conasis.t_horariosCursos', function (Blueprint $table) {
            $table->smallInteger('turno_id')->nullable()->after('nroDia')->comment('1=Mañana, 2=Tarde, 3=Noche');
            $table->string('nombreTurno', 20)->nullable()->after('turno_id');
        });
    }

    public function down(): void
    {
        Schema::table('conasis.t_horariosCursos', function (Blueprint $table) {
            $table->dropColumn(['turno_id', 'nombreTurno']);
        });
    }
};
