<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Corrige los permisos del perfil "Director IE".
 *
 * Un Director de IE solo debe poder administrar (ver/editar) su IE y
 * sus trabajadores. NO debe poder crear trabajadores, personas, IEs,
 * ni entidades.
 *
 * Se eliminan del perfil:
 * - personas.crear
 * - trabajadores.crear
 */
return new class extends Migration
{
    public function up(): void
    {
        $perfilId = DB::table('auth.perfiles')
            ->where('nombre', 'Director IE')
            ->value('id');

        if (! $perfilId) {
            return;
        }

        $permisosAQuitar = ['personas.crear', 'trabajadores.crear'];

        $permisoIds = DB::table('auth.permisos')
            ->whereIn('codigo', $permisosAQuitar)
            ->pluck('id')
            ->toArray();

        if (empty($permisoIds)) {
            return;
        }

        DB::table('auth.perfil_permisos')
            ->where('perfil_id', $perfilId)
            ->whereIn('permiso_id', $permisoIds)
            ->delete();
    }

    public function down(): void
    {
        $perfilId = DB::table('auth.perfiles')
            ->where('nombre', 'Director IE')
            ->value('id');

        if (! $perfilId) {
            return;
        }

        $permisosAAgregar = ['personas.crear', 'trabajadores.crear'];

        $permisoIds = DB::table('auth.permisos')
            ->whereIn('codigo', $permisosAAgregar)
            ->pluck('id')
            ->toArray();

        foreach ($permisoIds as $pid) {
            DB::table('auth.perfil_permisos')->insertOrIgnore([
                'perfil_id' => $perfilId,
                'permiso_id' => $pid,
            ]);
        }
    }
};
