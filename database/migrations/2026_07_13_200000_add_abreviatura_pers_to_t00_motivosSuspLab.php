<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('
            ALTER TABLE param."t00_motivosSuspLab"
                ADD COLUMN IF NOT EXISTS "abreviaturaPers" varchar(10) NULL
        ');

        // Inicializar con el mismo valor de abreviatura como default
        DB::statement('
            UPDATE param."t00_motivosSuspLab"
            SET "abreviaturaPers" = abreviatura
            WHERE "abreviaturaPers" IS NULL
        ');
    }

    public function down(): void
    {
        DB::statement('
            ALTER TABLE param."t00_motivosSuspLab"
                DROP COLUMN IF EXISTS "abreviaturaPers"
        ');
    }
};
