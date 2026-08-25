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

        // 4b. Crear o actualizar la entidad UGEL (necesaria para el contexto del admin)
        $tipoUgelId = DB::table('param.t00_tipoEntidad')->where('abreviatura', 'UGEL')->value('id') ?? 1;
        DB::table('t_entidades')->updateOrInsert(
            ['ruc' => '20453420520'],
            [
                'tipoEntidad_id' => $tipoUgelId,
                'razonSocial' => 'UGEL CAJAMARCA',
                'razonComercial' => 'UGEL CAJAMARCA',
                'activo' => true,
                'created_by' => 1,
                'created_at' => now(),
            ]
        );
        $ugelId = DB::table('t_entidades')->where('ruc', '20453420520')->value('id');

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

            // Horarios
            ['codigo' => 'horarios.ver', 'modulo' => 'horarios', 'descripcion' => 'Ver horarios de cursos y trabajadores'],
            ['codigo' => 'horarios.crear', 'modulo' => 'horarios', 'descripcion' => 'Crear y asignar horarios'],
            ['codigo' => 'horarios.editar', 'modulo' => 'horarios', 'descripcion' => 'Editar horarios existentes'],
            ['codigo' => 'horarios.eliminar', 'modulo' => 'horarios', 'descripcion' => 'Eliminar horarios'],

            // Infraestructura (locales, relojes, dispositivos)
            ['codigo' => 'infraestructura.ver', 'modulo' => 'infraestructura', 'descripcion' => 'Ver locales, relojes y dispositivos de marca'],
            ['codigo' => 'infraestructura.crear', 'modulo' => 'infraestructura', 'descripcion' => 'Crear locales, relojes y dispositivos'],
            ['codigo' => 'infraestructura.editar', 'modulo' => 'infraestructura', 'descripcion' => 'Editar locales y relojes'],
            ['codigo' => 'infraestructura.eliminar', 'modulo' => 'infraestructura', 'descripcion' => 'Eliminar locales y relojes'],

            // Asistencia
            ['codigo' => 'asistencia.ver', 'modulo' => 'asistencia', 'descripcion' => 'Ver reporte de asistencia y marcaciones'],
            ['codigo' => 'asistencia.crear', 'modulo' => 'asistencia', 'descripcion' => 'Registrar/Importar marcaciones'],
            ['codigo' => 'asistencia.editar', 'modulo' => 'asistencia', 'descripcion' => 'Editar o justificar asistencia'],
            ['codigo' => 'asistencia.consolidar', 'modulo' => 'asistencia', 'descripcion' => 'Consolidar asistencia mensual'],

            // Trámites (expedientes, justificaciones, suspensiones)
            ['codigo' => 'tramites.ver', 'modulo' => 'tramites', 'descripcion' => 'Ver expedientes y trámites'],
            ['codigo' => 'tramites.crear', 'modulo' => 'tramites', 'descripcion' => 'Crear expedientes y documentos de trámite'],
            ['codigo' => 'tramites.editar', 'modulo' => 'tramites', 'descripcion' => 'Editar expedientes y trámites'],
            ['codigo' => 'tramites.anular', 'modulo' => 'tramites', 'descripcion' => 'Anular expedientes registrados'],

            // Reportes
            ['codigo' => 'reportes.ver', 'modulo' => 'reportes', 'descripcion' => 'Ver y descargar reportes del sistema'],

            // Configuración
            ['codigo' => 'configuracion.ver', 'modulo' => 'configuracion', 'descripcion' => 'Ver áreas, cargos, condiciones y zonas'],
            ['codigo' => 'configuracion.editar', 'modulo' => 'configuracion', 'descripcion' => 'Gestionar áreas, cargos, condiciones y zonas'],
            ['codigo' => 'usuarios.gestionar', 'modulo' => 'configuracion', 'descripcion' => 'Gestionar usuarios, perfiles y permisos'],

            // Dashboard
            ['codigo' => 'dashboard.ver', 'modulo' => 'dashboard', 'descripcion' => 'Ver panel de control'],
            ['codigo' => 'dashboard.admin', 'modulo' => 'dashboard', 'descripcion' => 'Ver estadísticas globales de todas las IEs'],
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
        // Un Director solo administra su IE: ver/editar trabajadores y su IE,
        // NO puede crear trabajadores, IEs, ni entidades.
        $directorPermisos = [
            'personas.ver', 'personas.editar',
            'trabajadores.ver', 'trabajadores.editar',
            'instituciones.ver', 'instituciones.editar',
            'horarios.ver', 'horarios.crear', 'horarios.editar',
            'infraestructura.ver',
            'asistencia.ver', 'asistencia.crear', 'asistencia.editar', 'asistencia.consolidar',
            'tramites.ver', 'tramites.crear', 'tramites.editar',
            'reportes.ver',
            'dashboard.ver',
        ];
        $directorPermisoIds = DB::table('auth.permisos')->whereIn('codigo', $directorPermisos)->pluck('id')->toArray();
        foreach ($directorPermisoIds as $pid) {
            DB::table('auth.perfil_permisos')->updateOrInsert(
                ['perfil_id' => $directorPerfilId, 'permiso_id' => $pid]
            );
        }

        // Asignar permisos específicos a "Docente"
        // Un Docente puede ver su ficha de trabajador (datos, horarios, asistencia, expedientes)
        // y crear expedientes, pero NO puede crear altas ni editar datos.
        $docentePermisos = [
            'personas.ver',
            'trabajadores.ver',
            'asistencia.ver',
            'horarios.ver',
            'tramites.ver', 'tramites.crear',
        ];
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

        // 9. Asignar el perfil "Admin UGEL" al usuario como administrador de la UGEL creada
        DB::table('auth.usuario_perfil_ie')->updateOrInsert(
            [
                'user_id' => $user->id,
                'entidadUgel_id' => $ugelId,
                'institucionEducativa_id' => null,
            ],
            [
                'perfil_id' => $adminPerfilId,
                'activo' => true,
                'created_by' => 1,
                'created_at' => now(),
            ]
        );

        // 10. Asignar todos los permisos directos al usuario administrador (sin IE específica)
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
