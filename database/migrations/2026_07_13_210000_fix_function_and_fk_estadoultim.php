<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Corregir FK de t_asistencia.estadoUltim_id:
        //    Debe apuntar a t_estadosAsistencia (historial), no a t00_estadosAsis (catálogo).
        //    También cambiar el tipo a bigint para coincidir con t_estadosAsistencia.id
        DB::statement('
            ALTER TABLE conasis."t_asistencia"
                DROP CONSTRAINT IF EXISTS "t_asistencia_estadoultim_id_foreign"
        ');

        DB::statement('
            ALTER TABLE conasis."t_asistencia"
                DROP CONSTRAINT IF EXISTS "fk_t_asistencia_t00_estadosAsis_1"
        ');

        DB::statement('
            ALTER TABLE conasis."t_asistencia"
                ALTER COLUMN "estadoUltim_id" TYPE bigint
        ');

        DB::statement('
            ALTER TABLE conasis."t_asistencia"
                ADD CONSTRAINT fk_t_asistencia_estadosasistencia
                FOREIGN KEY ("estadoUltim_id")
                REFERENCES conasis."t_estadosAsistencia" (id)
                ON UPDATE CASCADE
                ON DELETE SET NULL
        ');

        // 2. Re-crear la función con dnl."institucionEduc_id" corregido
        $sql = file_get_contents(database_path('../pro/f_procesa_asismes_v1.txt'));
        // Remover el comentario del encabezado
        $sql = preg_replace('/\/\*.*?\*\//s', '', $sql, 1);
        DB::unprepared(trim($sql));
    }

    public function down(): void
    {
        DB::statement('
            ALTER TABLE conasis."t_asistencia"
                DROP CONSTRAINT IF EXISTS fk_t_asistencia_estadosasistencia
        ');

        DB::statement('
            ALTER TABLE conasis."t_asistencia"
                ALTER COLUMN "estadoUltim_id" TYPE smallint
        ');

        DB::statement('
            ALTER TABLE conasis."t_asistencia"
                ADD CONSTRAINT "t_asistencia_estadoultim_id_foreign"
                FOREIGN KEY ("estadoUltim_id")
                REFERENCES param."t00_estadosAsis" (id)
        ');
    }
};
