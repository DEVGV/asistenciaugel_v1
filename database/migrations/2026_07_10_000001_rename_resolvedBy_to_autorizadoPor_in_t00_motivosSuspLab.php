<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('param.t00_motivosSuspLab', function (Blueprint $table) {
            $table->renameColumn('resolvedBy', 'autorizadoPor');
        });
    }

    public function down(): void
    {
        Schema::table('param.t00_motivosSuspLab', function (Blueprint $table) {
            $table->renameColumn('autorizadoPor', 'resolvedBy');
        });
    }
};
