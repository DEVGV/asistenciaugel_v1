<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conasis.t_justificaciones', function (Blueprint $table) {
            $table->smallInteger('motivoSuspLab_id')->nullable()->after('altaTrabajador_id');
            $table->foreign('motivoSuspLab_id')->references('id')->on('param.t00_motivosSuspLab');
        });

        Schema::table('conasis.t_exoneracionesMarcacion', function (Blueprint $table) {
            $table->smallInteger('motivoSuspLab_id')->nullable()->after('altaTrabajador_id');
            $table->foreign('motivoSuspLab_id')->references('id')->on('param.t00_motivosSuspLab');
        });
    }

    public function down(): void
    {
        Schema::table('conasis.t_justificaciones', function (Blueprint $table) {
            $table->dropForeign(['motivoSuspLab_id']);
            $table->dropColumn('motivoSuspLab_id');
        });

        Schema::table('conasis.t_exoneracionesMarcacion', function (Blueprint $table) {
            $table->dropForeign(['motivoSuspLab_id']);
            $table->dropColumn('motivoSuspLab_id');
        });
    }
};
