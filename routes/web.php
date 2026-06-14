<?php

use App\Http\Controllers\Auth\ContextoController;
use App\Http\Controllers\Configuracion\AreaController;
use App\Http\Controllers\Configuracion\CargoController;
use App\Http\Controllers\Configuracion\CondicionLaboralController;
use App\Http\Controllers\Configuracion\PerfilController;
use App\Http\Controllers\Configuracion\UsuarioController;
use App\Http\Controllers\Configuracion\ZonaController;
use App\Http\Controllers\Entidad\EntidadController;
use App\Http\Controllers\Entidad\EntidadMasivaController;
use App\Http\Controllers\Horario\CargaHorariaController;
use App\Http\Controllers\Horario\HorarioCursoController;
use App\Http\Controllers\Horario\HorarioMasivoController;
use App\Http\Controllers\Horario\HorarioTrabajadorController;
use App\Http\Controllers\Infraestructura\DispositivoMarcaController;
use App\Http\Controllers\Infraestructura\LocalController;
use App\Http\Controllers\Infraestructura\LocalInstEducController;
use App\Http\Controllers\Infraestructura\LocalMarcacionController;
use App\Http\Controllers\Infraestructura\RelojController;
use App\Http\Controllers\Infraestructura\RelojesMasivaController;
use App\Http\Controllers\InstitucionEducativa\AltaMasivaIEController;
use App\Http\Controllers\InstitucionEducativa\CursoIEController;
use App\Http\Controllers\InstitucionEducativa\CursosMasivaIEController;
use App\Http\Controllers\InstitucionEducativa\DiasNoLaborablesController;
use App\Http\Controllers\InstitucionEducativa\GradoIEController;
use App\Http\Controllers\InstitucionEducativa\GradosMasivaIEController;
use App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController;
use App\Http\Controllers\InstitucionEducativa\InstitucionEducativaMasivaController;
use App\Http\Controllers\InstitucionEducativa\SeccionIEController;
use App\Http\Controllers\Persona\DomicilioController;
use App\Http\Controllers\Persona\EmailController;
use App\Http\Controllers\Persona\PersonaController;
use App\Http\Controllers\Persona\PersonaMasivaController;
use App\Http\Controllers\Persona\TelefonoController;
use App\Http\Controllers\Trabajador\AltaTrabajadorController;
use App\Http\Controllers\Tramite\PermisoController;
use App\Http\Controllers\Trabajador\RegistroTrabajadorController;
use App\Http\Controllers\Trabajador\TrabajadorController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

// ── Contexto de trabajo (UGEL / IE) ─────────────────────────────────────────
Route::middleware(['auth'])->group(function () {
    Route::get('seleccionar-contexto', [ContextoController::class, 'create'])
        ->name('contexto.seleccionar');
    Route::post('seleccionar-contexto', [ContextoController::class, 'store'])
        ->name('contexto.establecer');
    Route::inertia('sin-acceso', 'auth/SinAcceso')->name('sin-acceso');
});

Route::middleware(['auth', 'verified', 'contexto'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');

    Route::resource('entidades', EntidadController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->parameters(['entidades' => 'entidade']);
    Route::post('entidades-masivas', [EntidadMasivaController::class, 'store'])
        ->name('entidades.masivo.store');

    Route::resource('areas', AreaController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->parameters(['areas' => 'area']);

    Route::resource('cargos', CargoController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->parameters(['cargos' => 'cargo']);

    Route::resource('condiciones-laborales', CondicionLaboralController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->parameters(['condiciones-laborales' => 'condicionLaboral']);

    Route::resource('zonas', ZonaController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->parameters(['zonas' => 'zona']);

    // ── Usuarios ─────────────────────────────────────────────────────────────
    Route::resource('usuarios', UsuarioController::class)
        ->only(['index', 'show'])
        ->parameters(['usuarios' => 'usuario']);

    Route::post('usuarios/{usuario}/cambiar-password', [UsuarioController::class, 'cambiarPassword'])
        ->name('usuarios.cambiar-password');

    Route::post('usuarios/{usuario}/toggle-activo', [UsuarioController::class, 'toggleActivo'])
        ->name('usuarios.toggle-activo');

    Route::post('usuarios/{usuario}/perfiles', [UsuarioController::class, 'asignarPerfil'])
        ->name('usuarios.perfiles.asignar');

    Route::delete('usuarios/{usuario}/perfiles/{perfilIe}', [UsuarioController::class, 'revocarPerfil'])
        ->name('usuarios.perfiles.revocar');

    // ── Perfiles ──────────────────────────────────────────────────────────────
    Route::resource('perfiles', PerfilController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->parameters(['perfiles' => 'perfil']);

    Route::post('perfiles/{perfil}/permisos', [PerfilController::class, 'syncPermisos'])
        ->name('perfiles.permisos.sync');

    Route::resource('personas', PersonaController::class)
        ->only(['index', 'show', 'store', 'update', 'destroy']);
    Route::post('personas/{persona}/convertir-trabajador', [PersonaController::class, 'convertirTrabajador'])
        ->name('personas.convertir-trabajador');
    Route::post('personas-masivas', [PersonaMasivaController::class, 'store'])
        ->name('personas.masivo.store');

    Route::resource('personas.telefonos', TelefonoController::class)
        ->only(['store', 'update', 'destroy'])
        ->shallow();
    Route::patch('telefonos/{telefono}/dar-de-baja', [TelefonoController::class, 'darDeBaja'])
        ->name('telefonos.dar-de-baja');

    Route::resource('personas.emails', EmailController::class)
        ->only(['store', 'update', 'destroy'])
        ->shallow();
    Route::patch('emails/{email}/dar-de-baja', [EmailController::class, 'darDeBaja'])
        ->name('emails.dar-de-baja');

    Route::resource('personas.domicilios', DomicilioController::class)
        ->only(['store', 'update', 'destroy'])
        ->shallow();
    Route::patch('domicilios/{domicilio}/dar-de-baja', [DomicilioController::class, 'darDeBaja'])
        ->name('domicilios.dar-de-baja');

    Route::resource('trabajadores', TrabajadorController::class)
        ->except(['create', 'edit', 'update'])
        ->parameters(['trabajadores' => 'trabajador']);

    // Registro unificado de trabajador (persona + usuario + alta + perfil)
    Route::get('registro-trabajador', [RegistroTrabajadorController::class, 'create'])
        ->name('registro-trabajador.create');
    Route::post('registro-trabajador', [RegistroTrabajadorController::class, 'store'])
        ->name('registro-trabajador.store');
    Route::post('trabajadores-masivos', [RegistroTrabajadorController::class, 'storeMasivo'])
        ->name('trabajadores.masivo.store');

    // Sub-recurso shallow: store/update/destroy por trabajador o por alta directamente
    Route::resource('trabajadores.altas', AltaTrabajadorController::class)
        ->only(['store', 'update', 'destroy'])
        ->shallow()
        ->parameters(['trabajadores' => 'trabajador', 'altas' => 'alta']);

    // Ruta global paginada de todas las altas del sistema
    Route::get('altas', [AltaTrabajadorController::class, 'index'])->name('altas.index');

    // Registrar baja de un alta activa
    Route::post('altas/{alta}/baja', [AltaTrabajadorController::class, 'darBaja'])
        ->name('altas.baja');

    Route::resource('instituciones', InstitucionEducativaController::class)
        ->except(['create', 'edit'])
        ->parameters(['instituciones' => 'institucione']);

    // Carga masiva de nuevas Instituciones Educativas
    Route::post('instituciones-masivas', [InstitucionEducativaMasivaController::class, 'store'])
        ->name('instituciones.masivo.store');

    // Tab Docentes/Personal de una IE (altas paginadas)
    Route::get('instituciones/{institucione}/docentes', [InstitucionEducativaController::class, 'docentes'])
        ->name('instituciones.docentes');

    // Carga masiva de altas para una IE
    Route::post('instituciones/{institucione}/altas-masivas', [AltaMasivaIEController::class, 'store'])
        ->name('instituciones.altas-masivas.store');

    // Carga masiva de grados y secciones para una IE
    Route::post('instituciones/{institucione}/grados-masivos', [GradosMasivaIEController::class, 'store'])
        ->name('instituciones.grados-masivos.store');

    // Carga masiva de cursos para una IE
    Route::post('instituciones/{institucione}/cursos-masivos', [CursosMasivaIEController::class, 'store'])
        ->name('instituciones.cursos-masivos.store');

    // Días No Laborables por IE
    Route::resource('instituciones.dias-no-laborables', DiasNoLaborablesController::class)
        ->only(['store', 'update', 'destroy'])
        ->shallow()
        ->parameters(['instituciones' => 'institucione', 'dias-no-laborables' => 'diasNoLaborable']);

    Route::get('instituciones/{institucione}/dias-no-laborables/generar-feriados', [DiasNoLaborablesController::class, 'generarFeriados'])
        ->name('instituciones.dias-no-laborables.generar-feriados');

    Route::resource('instituciones.cursos', CursoIEController::class)
        ->only(['store', 'update', 'destroy'])
        ->shallow()
        ->parameters(['instituciones' => 'institucione']);

    Route::resource('instituciones.grados', GradoIEController::class)
        ->only(['store', 'update', 'destroy'])
        ->shallow()
        ->parameters(['instituciones' => 'institucione']);

    Route::resource('grados.secciones', SeccionIEController::class)
        ->only(['store', 'update', 'destroy'])
        ->shallow();

    // Infraestructura — Recursos independientes
    Route::resource('locales', LocalController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->parameters(['locales' => 'local']);

    Route::resource('dispositivos-marca', DispositivoMarcaController::class)
        ->only(['index', 'store', 'destroy'])
        ->parameters(['dispositivos-marca' => 'dispositivosMarca']);

    // Infraestructura — Sub-recursos de IE
    Route::resource('instituciones.locales-ie', LocalInstEducController::class)
        ->only(['store', 'destroy'])
        ->shallow()
        ->parameters(['instituciones' => 'institucione', 'locales-ie' => 'localesIe']);

    Route::resource('locales-ie.relojes', RelojController::class)
        ->only(['store', 'update', 'destroy'])
        ->shallow()
        ->parameters(['locales-ie' => 'localesIe', 'relojes' => 'reloje']);

    // Carga masiva de relojes para un local de IE
    Route::post('locales-ie/{localesIe}/relojes-masivos', [RelojesMasivaController::class, 'store'])
        ->name('locales-ie.relojes-masivos.store');

    Route::resource('locales-ie.marcaciones-local', LocalMarcacionController::class)
        ->only(['store', 'destroy'])
        ->shallow()
        ->parameters(['locales-ie' => 'localesIe', 'marcaciones-local' => 'marcacionesLocal']);

    Route::resource('horarios-cursos', HorarioCursoController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->parameters(['horarios-cursos' => 'horarioCurso']);

    // Carga masiva de horarios (crear + editar + asignar docente en lote)
    Route::post('horarios-masivos', [HorarioMasivoController::class, 'store'])
        ->name('horarios-masivos.store');

    Route::resource('horarios-cursos.cargas', CargaHorariaController::class)
        ->only(['store', 'update', 'destroy'])
        ->shallow()
        ->parameters(['horarios-cursos' => 'horarioCurso', 'cargas' => 'cargaHoraria']);

    Route::resource('horarios-trabajador', HorarioTrabajadorController::class)
        ->only(['index', 'show'])
        ->parameters(['horarios-trabajador' => 'horarioTrabajador']);

    // API: listar todos los horarios activos de un trabajador (para el tab Horarios en Show)
    Route::get('trabajadores/{trabajador}/horarios', [HorarioTrabajadorController::class, 'porTrabajador'])
        ->name('trabajadores.horarios');

    // ── Trámite: Solicitudes de Permiso ──────────────────────────────────────
    // Listado para el tab Permisos del trabajador
    Route::get('trabajadores/{trabajador}/permisos', [PermisoController::class, 'porTrabajador'])
        ->name('trabajadores.permisos');

    // Listado paginado para el tab Permisos de una IE
    Route::get('instituciones/{institucione}/permisos', [PermisoController::class, 'porInstitucion'])
        ->name('instituciones.permisos');

    // Altas activas de una IE (opciones del select al crear desde la IE)
    Route::get('instituciones/{institucione}/permisos-altas', [PermisoController::class, 'altasPorInstitucion'])
        ->name('instituciones.permisos.altas');

    // Crear solicitud de permiso (expediente + sustento + detalle)
    Route::post('permisos', [PermisoController::class, 'store'])->name('permisos.store');

    // Validar (aprobar/rechazar) una solicitud — pestaña de la IE
    Route::post('permisos/{expediente}/validar', [PermisoController::class, 'validar'])
        ->name('permisos.validar');

    // Anular una solicitud pendiente
    Route::post('permisos/{expediente}/anular', [PermisoController::class, 'anular'])
        ->name('permisos.anular');

    // Descargar el documento de sustento
    Route::get('documentos-tram/{documentoTram}/descargar', [PermisoController::class, 'descargarSustento'])
        ->name('documentos-tram.descargar');
});

require __DIR__.'/settings.php';
require __DIR__.'/api.php';
