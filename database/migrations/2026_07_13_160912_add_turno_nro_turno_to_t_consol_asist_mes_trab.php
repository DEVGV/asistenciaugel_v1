<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /** @var string Schema de la tabla objetivo */
    protected string $schema = 'conasis';

    /** @var string Tabla objetivo */
    protected string $table = 't_consolAsistMesTrab';

    /**
     * Agrega turno_id (FK → param.t00_turnos) y nroTurno (smallint nullable)
     * a conasis.t_consolAsistMesTrab para alinear la estructura con la
     * función almacenada f_procesa_asismes_v1.
     */
    public function up(): void
    {
        DB::statement(<<<SQL
            ALTER TABLE "{$this->schema}"."{$this->table}"
                ADD COLUMN IF NOT EXISTS turno_id smallint NULL,
                ADD COLUMN IF NOT EXISTS "nroTurno" smallint NULL
        SQL);

        DB::statement(<<<SQL
            ALTER TABLE "{$this->schema}"."{$this->table}"
                ADD CONSTRAINT fk_consolasist_turno
                FOREIGN KEY (turno_id)
                REFERENCES param.t00_turnos (id)
                ON UPDATE CASCADE
                ON DELETE SET NULL
        SQL);
    }

    /**
     * Revierte los cambios agregados en up().
     */
    public function down(): void
    {
        DB::statement(<<<SQL
            ALTER TABLE "{$this->schema}"."{$this->table}"
                DROP CONSTRAINT IF EXISTS fk_consolasist_turno
        SQL);

        DB::statement(<<<SQL
            ALTER TABLE "{$this->schema}"."{$this->table}"
                DROP COLUMN IF EXISTS turno_id,
                DROP COLUMN IF EXISTS "nroTurno"
        SQL);
    }
};
