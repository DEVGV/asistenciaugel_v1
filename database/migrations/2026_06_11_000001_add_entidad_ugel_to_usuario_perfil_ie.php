<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agrega entidadUgel_id a auth.usuario_perfil_ie para soportar multi-UGEL.
     *
     * Semántica de una asignación:
     * - institucionEducativa_id set            → asignación a una IE específica (la UGEL se deriva/copia de la IE)
     * - institucionEducativa_id null + UGEL set → administrador de esa UGEL (acceso a todas sus IEs)
     * - ambos null                              → administrador global del sistema (todas las UGELs)
     */
    public function up(): void
    {
        Schema::table('auth.usuario_perfil_ie', function (Blueprint $table) {
            $table->bigInteger('entidadUgel_id')->nullable()->after('perfil_id');
            $table->foreign('entidadUgel_id')->references('id')->on('t_entidades');
        });

        // Backfill: copiar la UGEL desde la IE para las asignaciones existentes por IE
        DB::statement('
            UPDATE auth.usuario_perfil_ie upi
            SET "entidadUgel_id" = ie."entidadUgel_id"
            FROM "t_institucionesEduc" ie
            WHERE upi."institucionEducativa_id" = ie.id
              AND upi."entidadUgel_id" IS NULL
        ');

        // Reemplazar el unique (user_id, institucionEducativa_id) por índices parciales:
        // un perfil por usuario+IE, y un perfil por usuario+UGEL cuando es admin de UGEL.
        Schema::table('auth.usuario_perfil_ie', function (Blueprint $table) {
            $table->dropUnique('uq_usuario_ie');
        });

        DB::statement('
            CREATE UNIQUE INDEX uq_usuario_ie
            ON auth.usuario_perfil_ie (user_id, "institucionEducativa_id")
            WHERE "institucionEducativa_id" IS NOT NULL
        ');

        DB::statement('
            CREATE UNIQUE INDEX uq_usuario_ugel
            ON auth.usuario_perfil_ie (user_id, "entidadUgel_id")
            WHERE "institucionEducativa_id" IS NULL AND "entidadUgel_id" IS NOT NULL
        ');
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS auth.uq_usuario_ugel');
        DB::statement('DROP INDEX IF EXISTS auth.uq_usuario_ie');

        Schema::table('auth.usuario_perfil_ie', function (Blueprint $table) {
            $table->unique(['user_id', 'institucionEducativa_id'], 'uq_usuario_ie');
            $table->dropForeign(['entidadUgel_id']);
            $table->dropColumn('entidadUgel_id');
        });
    }
};
