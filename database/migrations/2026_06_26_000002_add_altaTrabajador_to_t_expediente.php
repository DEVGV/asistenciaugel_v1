<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conasis.t_expediente', function (Blueprint $table) {
            $table->unsignedBigInteger('altaTrabajador_id')->nullable()->after('trabajador_id');
            $table->foreign('altaTrabajador_id')->references('id')->on('t_altasTrabajadores');
        });
    }

    public function down(): void
    {
        Schema::table('conasis.t_expediente', function (Blueprint $table) {
            $table->dropForeign(['altaTrabajador_id']);
            $table->dropColumn('altaTrabajador_id');
        });
    }
};
