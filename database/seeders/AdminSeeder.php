<?php

namespace Database\Seeders;

use App\Models\Personas;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Obtener IDs de parámetros necesarios
        $sexoM = DB::table('param.t00_sexos')->where('codigo', 'M')->orWhere('nombre', 'ILIKE', '%masculino%')->value('id') ?? 1;
        $dniId = DB::table('param.t3_tipoDocIdentidad')->where('abreviatura', 'ILIKE', '%DNI%')->value('id') ?? 1;
        $paisPE = DB::table('param.t26_pais')->where('codigo', 'PE')->orWhere('nombre', 'ILIKE', '%perú%')->value('id') ?? 1;

        // 2. Crear o actualizar la persona
        $persona = Personas::updateOrCreate(
            ['docIdentidad' => '76955033'],
            [
                'pais_id' => $paisPE,
                'tipoDocIdentidad_id' => $dniId,
                'paterno' => 'ROJAS',
                'materno' => 'SOLIS',
                'nombre' => 'JOHAN ALEXANDER',
                'sexo_id' => $sexoM,
                'activo' => true,
                'created_by' => 1,
                'created_at' => now(),
            ]
        );

        // 3. Crear o actualizar el email asociado
        DB::table('t_emails')->updateOrInsert(
            ['persona_id' => $persona->id],
            [
                'email' => 'johan.rojas@gmail.com',
                'personalInst' => 'P',
                'fechaInicio' => '2026-01-01',
                'created_by' => 1,
                'created_at' => now(),
            ]
        );

        // 4. Crear o actualizar el trabajador asociado
        DB::table('t_trabajador')->updateOrInsert(
            ['persona_id' => $persona->id],
            [
                'codigo' => 'TRAB-76955033',
                'activo' => true,
                'created_at' => now(),
            ]
        );
        $trabajadorId = DB::table('t_trabajador')->where('persona_id', $persona->id)->value('id');

        // 5. Crear o actualizar los permisos por defecto
        $permisosData = [
            // Personas
            ['codigo' => 'personas.ver', 'modulo' => 'personas', 'descripcion' => 'Ver listado y detalle de personas'],
            ['codigo' => 'personas.crear', 'modulo' => 'personas', 'descripcion' => 'Crear nuevas personas'],
            ['codigo' => 'personas.editar', 'modulo' => 'personas', 'descripcion' => 'Editar personas existentes'],
            ['codigo' => 'personas.eliminar', 'modulo' => 'personas', 'descripcion' => 'Desactivar personas'],

            // Trabajadores
            ['codigo' => 'trabajadores.ver', 'modulo' => 'trabajadores', 'descripcion' => 'Ver listado y detalle de trabajadores'],
            ['codigo' => 'trabajadores.crear', 'modulo' => 'trabajadores', 'descripcion' => 'Crear nuevos trabajadores y altas'],
            ['codigo' => 'trabajadores.editar', 'modulo' => 'trabajadores', 'descripcion' => 'Editar trabajadores y altas'],
            ['codigo' => 'trabajadores.eliminar', 'modulo' => 'trabajadores', 'descripcion' => 'Desactivar trabajadores'],

            // Instituciones Educativas
            ['codigo' => 'instituciones.ver', 'modulo' => 'instituciones', 'descripcion' => 'Ver listado y detalle de IEs'],
            ['codigo' => 'instituciones.crear', 'modulo' => 'instituciones', 'descripcion' => 'Crear nuevas IEs y subrecursos'],
            ['codigo' => 'instituciones.editar', 'modulo' => 'instituciones', 'descripcion' => 'Editar IEs y subrecursos'],
            ['codigo' => 'instituciones.eliminar', 'modulo' => 'instituciones', 'descripcion' => 'Desactivar IEs'],

            // Entidades
            ['codigo' => 'entidades.ver', 'modulo' => 'entidades', 'descripcion' => 'Ver listado y detalle de entidades'],
            ['codigo' => 'entidades.crear', 'modulo' => 'entidades', 'descripcion' => 'Crear nuevas entidades'],
            ['codigo' => 'entidades.editar', 'modulo' => 'entidades', 'descripcion' => 'Editar entidades existentes'],
            ['codigo' => 'entidades.eliminar', 'modulo' => 'entidades', 'descripcion' => 'Desactivar entidades'],

            // Asistencia
            ['codigo' => 'asistencia.ver', 'modulo' => 'asistencia', 'descripcion' => 'Ver reporte de asistencia y marcaciones'],
            ['codigo' => 'asistencia.crear', 'modulo' => 'asistencia', 'descripcion' => 'Registrar/Importar marcaciones'],
            ['codigo' => 'asistencia.editar', 'modulo' => 'asistencia', 'descripcion' => 'Editar o justificar asistencia'],

            // Configuración
            ['codigo' => 'configuracion.ver', 'modulo' => 'configuracion', 'descripcion' => 'Ver áreas, cargos, condiciones y zonas'],
            ['codigo' => 'configuracion.editar', 'modulo' => 'configuracion', 'descripcion' => 'Gestionar áreas, cargos, condiciones y zonas'],
            ['codigo' => 'usuarios.gestionar', 'modulo' => 'configuracion', 'descripcion' => 'Gestionar usuarios, perfiles y permisos'],
        ];

        foreach ($permisosData as $p) {
            DB::table('auth.permisos')->updateOrInsert(
                ['codigo' => $p['codigo']],
                ['modulo' => $p['modulo'], 'descripcion' => $p['descripcion'], 'activo' => true]
            );
        }

        // 6. Crear o actualizar los perfiles por defecto
        $perfilesData = [
            ['nombre' => 'Admin UGEL', 'descripcion' => 'Administrador global del sistema UGEL'],
            ['nombre' => 'Director IE', 'descripcion' => 'Director de Institución Educativa'],
            ['nombre' => 'Docente', 'descripcion' => 'Docente o Personal de IE'],
        ];

        foreach ($perfilesData as $p) {
            DB::table('auth.perfiles')->updateOrInsert(
                ['nombre' => $p['nombre']],
                ['descripcion' => $p['descripcion'], 'activo' => true]
            );
        }

        // Obtener IDs de perfiles creados
        $adminPerfilId = DB::table('auth.perfiles')->where('nombre', 'Admin UGEL')->value('id');
        $directorPerfilId = DB::table('auth.perfiles')->where('nombre', 'Director IE')->value('id');
        $docentePerfilId = DB::table('auth.perfiles')->where('nombre', 'Docente')->value('id');

        // 7. Asignar todos los permisos a "Admin UGEL"
        $allPermisos = DB::table('auth.permisos')->get();
        foreach ($allPermisos as $p) {
            DB::table('auth.perfil_permisos')->updateOrInsert(
                ['perfil_id' => $adminPerfilId, 'permiso_id' => $p->id]
            );
        }

        // Asignar permisos específicos a "Director IE"
        $directorPermisos = [
            'personas.ver', 'personas.crear', 'personas.editar',
            'trabajadores.ver', 'trabajadores.crear', 'trabajadores.editar',
            'instituciones.ver', 'instituciones.editar',
            'asistencia.ver', 'asistencia.crear', 'asistencia.editar',
        ];
        $directorPermisoIds = DB::table('auth.permisos')->whereIn('codigo', $directorPermisos)->pluck('id')->toArray();
        foreach ($directorPermisoIds as $pid) {
            DB::table('auth.perfil_permisos')->updateOrInsert(
                ['perfil_id' => $directorPerfilId, 'permiso_id' => $pid]
            );
        }

        // Asignar permisos específicos a "Docente"
        $docentePermisos = ['asistencia.ver'];
        $docentePermisoIds = DB::table('auth.permisos')->whereIn('codigo', $docentePermisos)->pluck('id')->toArray();
        foreach ($docentePermisoIds as $pid) {
            DB::table('auth.perfil_permisos')->updateOrInsert(
                ['perfil_id' => $docentePerfilId, 'permiso_id' => $pid]
            );
        }

        // 8. Crear o actualizar el usuario administrador
        $user = User::updateOrCreate(
            ['login' => '76955033'],
            [
                'trabajador_id' => $trabajadorId,
                'password' => '76955033', // Se encripta automáticamente por el cast del modelo User
                'activo' => true,
            ]
        );

        // 9. Asignar el perfil "Admin UGEL" al usuario (globalmente, institucionEducativa_id = null)
        DB::table('auth.usuario_perfil_ie')->updateOrInsert(
            [
                'user_id' => $user->id,
                'institucionEducativa_id' => null,
            ],
            [
                'perfil_id' => $adminPerfilId,
                'activo' => true,
                'created_by' => 1,
                'created_at' => now(),
            ]
        );

        // 10. Asignar todos los permisos directos al usuario administrador (globalmente, institucionEducativa_id = null)
        // Para que coincida con la lógica de permisos directos por IE
        foreach ($allPermisos as $p) {
            DB::table('auth.usuario_permisos_ie')->updateOrInsert(
                [
                    'user_id' => $user->id,
                    'permiso_id' => $p->id,
                    'institucionEducativa_id' => null,
                ],
                [
                    'created_by' => 1,
                    'created_at' => now(),
                ]
            );
        }
    }
}
