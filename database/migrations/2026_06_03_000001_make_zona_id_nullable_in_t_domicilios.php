<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('t_domicilios', function (Blueprint $table) {
            $table->bigInteger('zona_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('t_domicilios', function (Blueprint $table) {
            $table->bigInteger('zona_id')->nullable(false)->change();
        });
    }
};
