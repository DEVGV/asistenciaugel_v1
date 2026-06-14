<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('CREATE SCHEMA IF NOT EXISTS auth;');
        DB::statement('CREATE SCHEMA IF NOT EXISTS param;');
        DB::statement('CREATE SCHEMA IF NOT EXISTS public;');
        DB::statement('CREATE SCHEMA IF NOT EXISTS conasis;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP SCHEMA IF EXISTS auth CASCADE;');
        DB::statement('DROP SCHEMA IF EXISTS param CASCADE;');
        DB::statement('DROP SCHEMA IF EXISTS conasis CASCADE;');
        // Avoid dropping public schema as it's the default
    }
};
