<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('param.t00_motivosSuspLab', function (Blueprint $table) {
            // 'D' = Director, 'U' = UGEL — quién resuelve/aprueba el trámite
            $table->string('resolvedBy', 1)->default('D')->after('codigoProg');
        });
    }

    public function down(): void
    {
        Schema::table('param.t00_motivosSuspLab', function (Blueprint $table) {
            $table->dropColumn('resolvedBy');
        });
    }
};
