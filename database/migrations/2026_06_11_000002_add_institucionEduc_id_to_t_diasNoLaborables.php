<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conasis.t_diasNoLaborables', function (Blueprint $table) {
            $table->bigInteger('institucionEduc_id')->nullable()->after('feriado_id');
            $table->foreign('institucionEduc_id')
                ->references('id')
                ->on('public.t_institucionesEduc');
        });
    }

    public function down(): void
    {
        Schema::table('conasis.t_diasNoLaborables', function (Blueprint $table) {
            $table->dropForeign(['institucionEduc_id']);
            $table->dropColumn('institucionEduc_id');
        });
    }
};
