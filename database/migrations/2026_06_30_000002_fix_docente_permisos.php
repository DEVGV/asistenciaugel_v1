<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Ajusta los permisos del perfil "Docente".
 *
 * Un Docente solo accede a su propia ficha de trabajador (todas las pestañas)
 * y puede crear expedientes. NO accede al dashboard, listados ni otras vistas.
 *
 * Se agregan:  personas.ver, trabajadores.ver, tramites.crear
 * Se quita:    dashboard.ver (el Docente es redirigido a su ficha)
 */
return new class extends Migration
{
    public function up(): void
    {
        $perfilId = DB::table('auth.perfiles')
            ->where('nombre', 'Docente')
            ->value('id');

        if (! $perfilId) {
            return;
        }

        // Agregar permisos necesarios
        $permisosAAgregar = ['personas.ver', 'trabajadores.ver', 'tramites.crear'];
        $agregarIds = DB::table('auth.permisos')
            ->whereIn('codigo', $permisosAAgregar)
            ->pluck('id')
            ->toArray();

        foreach ($agregarIds as $pid) {
            DB::table('auth.perfil_permisos')->insertOrIgnore([
                'perfil_id' => $perfilId,
                'permiso_id' => $pid,
            ]);
        }

        // Quitar dashboard.ver
        $dashboardId = DB::table('auth.permisos')
            ->where('codigo', 'dashboard.ver')
            ->value('id');

        if ($dashboardId) {
            DB::table('auth.perfil_permisos')
                ->where('perfil_id', $perfilId)
                ->where('permiso_id', $dashboardId)
                ->delete();
        }
    }

    public function down(): void
    {
        $perfilId = DB::table('auth.perfiles')
            ->where('nombre', 'Docente')
            ->value('id');

        if (! $perfilId) {
            return;
        }

        // Restaurar dashboard.ver
        $dashboardId = DB::table('auth.permisos')
            ->where('codigo', 'dashboard.ver')
            ->value('id');

        if ($dashboardId) {
            DB::table('auth.perfil_permisos')->insertOrIgnore([
                'perfil_id' => $perfilId,
                'permiso_id' => $dashboardId,
            ]);
        }

        // Quitar los permisos que se agregaron
        $permisosAQuitar = ['personas.ver', 'trabajadores.ver', 'tramites.crear'];
        $quitarIds = DB::table('auth.permisos')
            ->whereIn('codigo', $permisosAQuitar)
            ->pluck('id')
            ->toArray();

        if (! empty($quitarIds)) {
            DB::table('auth.perfil_permisos')
                ->where('perfil_id', $perfilId)
                ->whereIn('permiso_id', $quitarIds)
                ->delete();
        }
    }
};
