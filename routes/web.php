<?php

use App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController;
use App\Http\Controllers\Auth\ContextoController;
use App\Http\Controllers\Configuracion\AreaController;
use App\Http\Controllers\Configuracion\CargoController;
use App\Http\Controllers\Configuracion\CondicionLaboralController;
use App\Http\Controllers\Configuracion\PerfilController;
use App\Http\Controllers\Configuracion\UsuarioController;
use App\Http\Controllers\Configuracion\ZonaController;
use App\Http\Controllers\DashboardController;
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
use App\Http\Controllers\InstitucionEducativa\DomicilioIEController;
use App\Http\Controllers\InstitucionEducativa\EmailIEController;
use App\Http\Controllers\InstitucionEducativa\GradoIEController;
use App\Http\Controllers\InstitucionEducativa\GradosMasivaIEController;
use App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController;
use App\Http\Controllers\InstitucionEducativa\InstitucionEducativaMasivaController;
use App\Http\Controllers\InstitucionEducativa\SeccionIEController;
use App\Http\Controllers\InstitucionEducativa\TelefonoIEController;
use App\Http\Controllers\Marcacion\CargaMarcacionesController;
use App\Http\Controllers\Persona\DomicilioController;
use App\Http\Controllers\Persona\EmailController;
use App\Http\Controllers\Persona\TelefonoController;
use App\Http\Controllers\Trabajador\AltaTrabajadorController;
use App\Http\Controllers\Trabajador\MarcacionesTrabajadorController;
use App\Http\Controllers\Trabajador\RegistroTrabajadorController;
use App\Http\Controllers\Trabajador\TrabajadorController;
use App\Http\Controllers\Tramite\ExpedienteController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login')->name('home');

// ── Contexto de trabajo (UGEL / IE) ─────────────────────────────────────────
Route::middleware(['auth'])->group(function () {
    Route::get('seleccionar-contexto', [ContextoController::class, 'create'])
        ->name('contexto.seleccionar');
    Route::post('seleccionar-contexto', [ContextoController::class, 'store'])
        ->name('contexto.establecer');
    Route::inertia('sin-acceso', 'auth/SinAcceso')->name('sin-acceso');
});

Route::middleware(['auth', 'verified', 'contexto'])->group(function () {
    // ── Dashboard ─────────────────────────────────────────────────────────────
    // Docentes son redirigidos a su propia ficha de trabajador.
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    // ── Entidades ────────────────────────────────────────────────────────────
    Route::middleware('permiso:entidades.ver')->group(function () {
        Route::resource('entidades', EntidadController::class)
            ->only(['index'])
            ->parameters(['entidades' => 'entidade']);
    });
    Route::middleware('permiso:entidades.crear,entidades.editar')->group(function () {
        Route::resource('entidades', EntidadController::class)
            ->only(['store', 'update'])
            ->parameters(['entidades' => 'entidade']);
        Route::post('entidades-masivas', [EntidadMasivaController::class, 'store'])
            ->name('entidades.masivo.store');
    });
    Route::middleware('permiso:entidades.eliminar')->group(function () {
        Route::resource('entidades', EntidadController::class)
            ->only(['destroy'])
            ->parameters(['entidades' => 'entidade']);
    });

    // ── Configuración (áreas, cargos, condiciones, zonas) ────────────────────
    Route::middleware('permiso:configuracion.ver')->group(function () {
        Route::resource('areas', AreaController::class)
            ->only(['index'])
            ->parameters(['areas' => 'area']);
        Route::resource('cargos', CargoController::class)
            ->only(['index'])
            ->parameters(['cargos' => 'cargo']);
        Route::resource('condiciones-laborales', CondicionLaboralController::class)
            ->only(['index'])
            ->parameters(['condiciones-laborales' => 'condicionLaboral']);
        Route::resource('zonas', ZonaController::class)
            ->only(['index'])
            ->parameters(['zonas' => 'zona']);
    });
    Route::middleware('permiso:configuracion.editar')->group(function () {
        Route::resource('areas', AreaController::class)
            ->only(['store', 'update', 'destroy'])
            ->parameters(['areas' => 'area']);
        Route::resource('cargos', CargoController::class)
            ->only(['store', 'update', 'destroy'])
            ->parameters(['cargos' => 'cargo']);
        Route::resource('condiciones-laborales', CondicionLaboralController::class)
            ->only(['store', 'update', 'destroy'])
            ->parameters(['condiciones-laborales' => 'condicionLaboral']);
        Route::resource('zonas', ZonaController::class)
            ->only(['store', 'update', 'destroy'])
            ->parameters(['zonas' => 'zona']);
    });

    // ── Usuarios y Perfiles ──────────────────────────────────────────────────
    Route::middleware('permiso:usuarios.gestionar')->group(function () {
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

        Route::resource('perfiles', PerfilController::class)
            ->only(['index', 'store', 'update', 'destroy'])
            ->parameters(['perfiles' => 'perfil']);
        Route::post('perfiles/{perfil}/permisos', [PerfilController::class, 'syncPermisos'])
            ->name('perfiles.permisos.sync');
    });

    // ── Trabajadores ─────────────────────────────────────────────────────────
    Route::middleware('permiso:trabajadores.ver')->group(function () {
        Route::resource('trabajadores', TrabajadorController::class)
            ->only(['index', 'show'])
            ->parameters(['trabajadores' => 'trabajador']);

        Route::get('trabajadores/{trabajador}/{tab}', [TrabajadorController::class, 'showTab'])
            ->where('tab', 'laboral|horario|asistencia|permisos')
            ->name('trabajadores.show-tab');

        Route::get('altas', [AltaTrabajadorController::class, 'index'])->name('altas.index');

        // API: horarios y marcaciones del trabajador (lectura)
        Route::get('trabajadores/{trabajador}/horarios', [HorarioTrabajadorController::class, 'porTrabajador'])
            ->name('trabajadores.horarios');
        Route::get('trabajadores/{trabajador}/marcaciones', [MarcacionesTrabajadorController::class, 'porTrabajador'])
            ->name('trabajadores.marcaciones');
        Route::get('trabajadores/{trabajador}/asistencia-consolidada', [MarcacionesTrabajadorController::class, 'asistenciaConsolidada'])
            ->name('trabajadores.asistencia-consolidada');
    });
    Route::middleware('permiso:trabajadores.crear')->group(function () {
        Route::resource('trabajadores', TrabajadorController::class)
            ->only(['store'])
            ->parameters(['trabajadores' => 'trabajador']);

        Route::get('registro-trabajador', [RegistroTrabajadorController::class, 'create'])
            ->name('registro-trabajador.create');
        Route::post('registro-trabajador', [RegistroTrabajadorController::class, 'store'])
            ->name('registro-trabajador.store');
        Route::post('trabajadores-masivos', [RegistroTrabajadorController::class, 'storeMasivo'])
            ->name('trabajadores.masivo.store');

        Route::resource('trabajadores.altas', AltaTrabajadorController::class)
            ->only(['store'])
            ->shallow()
            ->parameters(['trabajadores' => 'trabajador', 'altas' => 'alta']);
    });
    Route::middleware('permiso:trabajadores.editar')->group(function () {
        Route::resource('trabajadores', TrabajadorController::class)
            ->only(['update'])
            ->parameters(['trabajadores' => 'trabajador']);

        Route::resource('trabajadores.altas', AltaTrabajadorController::class)
            ->only(['update'])
            ->shallow()
            ->parameters(['trabajadores' => 'trabajador', 'altas' => 'alta']);

        Route::post('altas/{alta}/baja', [AltaTrabajadorController::class, 'darBaja'])
            ->name('altas.baja');
    });
    Route::middleware('permiso:trabajadores.eliminar')->group(function () {
        Route::resource('trabajadores', TrabajadorController::class)
            ->only(['destroy'])
            ->parameters(['trabajadores' => 'trabajador']);

        Route::resource('trabajadores.altas', AltaTrabajadorController::class)
            ->only(['destroy'])
            ->shallow()
            ->parameters(['trabajadores' => 'trabajador', 'altas' => 'alta']);
    });

    // ── Contacto de persona (sub-recurso de trabajadores) ────────────────────
    Route::middleware('permiso:personas.ver,personas.editar')->group(function () {
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
    });

    // ── Instituciones Educativas ─────────────────────────────────────────────
    Route::middleware('permiso:instituciones.ver')->group(function () {
        Route::resource('instituciones', InstitucionEducativaController::class)
            ->only(['index', 'show'])
            ->parameters(['instituciones' => 'institucione']);

        Route::get('instituciones/{institucione}/{tab}', [InstitucionEducativaController::class, 'showTab'])
            ->where('tab', 'cursos|grados|locales|permisos|no-laborables|consolidado-asistencia')
            ->name('instituciones.show-tab');

        Route::get('instituciones/{institucione}/docentes', [InstitucionEducativaController::class, 'docentes'])
            ->name('instituciones.docentes');

        // Consolidado de Asistencia
        Route::post('instituciones/{institucione}/consolidado-asistencia/procesar', [ConsolidadoAsistenciaController::class, 'procesar'])
            ->name('instituciones.consolidado-asistencia.procesar');
        Route::post('instituciones/{institucione}/consolidado-asistencia/reprocesar-trabajador', [ConsolidadoAsistenciaController::class, 'reprocesarTrabajador'])
            ->name('instituciones.consolidado-asistencia.reprocesar-trabajador');
        Route::get('instituciones/{institucione}/consolidado-asistencia/consultar', [ConsolidadoAsistenciaController::class, 'consultar'])
            ->name('instituciones.consolidado-asistencia.consultar');
        Route::get('consolidado-asistencia/{asistencia}/detalle', [ConsolidadoAsistenciaController::class, 'detalle'])
            ->name('consolidado-asistencia.detalle');

        // Reportes PDF de asistencia
        Route::get('instituciones/{institucione}/consolidado-asistencia/reporte1', [ConsolidadoAsistenciaController::class, 'reporte1'])
            ->name('instituciones.consolidado-asistencia.reporte1');
        Route::get('instituciones/{institucione}/consolidado-asistencia/reporte2', [ConsolidadoAsistenciaController::class, 'reporte2'])
            ->name('instituciones.consolidado-asistencia.reporte2');
    });
    Route::middleware('permiso:instituciones.crear')->group(function () {
        Route::resource('instituciones', InstitucionEducativaController::class)
            ->only(['store'])
            ->parameters(['instituciones' => 'institucione']);
        Route::post('instituciones-masivas', [InstitucionEducativaMasivaController::class, 'store'])
            ->name('instituciones.masivo.store');
    });
    Route::middleware('permiso:instituciones.editar')->group(function () {
        Route::resource('instituciones', InstitucionEducativaController::class)
            ->only(['update'])
            ->parameters(['instituciones' => 'institucione']);

        // Sub-recursos de IE: altas masivas, grados masivos, cursos masivos
        Route::post('instituciones/{institucione}/altas-masivas', [AltaMasivaIEController::class, 'store'])
            ->name('instituciones.altas-masivas.store');
        Route::post('instituciones/{institucione}/grados-masivos', [GradosMasivaIEController::class, 'store'])
            ->name('instituciones.grados-masivos.store');
        Route::post('instituciones/{institucione}/cursos-masivos', [CursosMasivaIEController::class, 'store'])
            ->name('instituciones.cursos-masivos.store');

        // Contacto de IE
        Route::resource('instituciones.telefonos-ie', TelefonoIEController::class)
            ->only(['store', 'update', 'destroy'])
            ->shallow()
            ->parameters(['instituciones' => 'institucione', 'telefonos-ie' => 'telefono']);
        Route::patch('instituciones-telefonos/{telefono}/dar-de-baja', [TelefonoIEController::class, 'darDeBaja'])
            ->name('instituciones.telefonos-ie.dar-de-baja');

        Route::resource('instituciones.emails-ie', EmailIEController::class)
            ->only(['store', 'update', 'destroy'])
            ->shallow()
            ->parameters(['instituciones' => 'institucione', 'emails-ie' => 'email']);
        Route::patch('instituciones-emails/{email}/dar-de-baja', [EmailIEController::class, 'darDeBaja'])
            ->name('instituciones.emails-ie.dar-de-baja');

        Route::resource('instituciones.domicilios-ie', DomicilioIEController::class)
            ->only(['store', 'update', 'destroy'])
            ->shallow()
            ->parameters(['instituciones' => 'institucione', 'domicilios-ie' => 'domicilio']);
        Route::patch('instituciones-domicilios/{domicilio}/dar-de-baja', [DomicilioIEController::class, 'darDeBaja'])
            ->name('instituciones.domicilios-ie.dar-de-baja');

        // Días No Laborables por IE
        Route::resource('instituciones.dias-no-laborables', DiasNoLaborablesController::class)
            ->only(['store', 'update', 'destroy'])
            ->shallow()
            ->parameters(['instituciones' => 'institucione', 'dias-no-laborables' => 'diasNoLaborable']);
        Route::get('instituciones/{institucione}/dias-no-laborables/generar-feriados', [DiasNoLaborablesController::class, 'generarFeriados'])
            ->name('instituciones.dias-no-laborables.generar-feriados');

        // Cursos, grados, secciones
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
    });
    Route::middleware('permiso:instituciones.eliminar')->group(function () {
        Route::resource('instituciones', InstitucionEducativaController::class)
            ->only(['destroy'])
            ->parameters(['instituciones' => 'institucione']);
    });

    // ── Infraestructura ──────────────────────────────────────────────────────
    Route::middleware('permiso:infraestructura.ver')->group(function () {
        Route::resource('locales', LocalController::class)
            ->only(['index'])
            ->parameters(['locales' => 'local']);
        Route::resource('dispositivos-marca', DispositivoMarcaController::class)
            ->only(['index'])
            ->parameters(['dispositivos-marca' => 'dispositivosMarca']);
    });
    Route::middleware('permiso:infraestructura.crear,infraestructura.editar')->group(function () {
        Route::resource('locales', LocalController::class)
            ->only(['store', 'update'])
            ->parameters(['locales' => 'local']);
        Route::resource('dispositivos-marca', DispositivoMarcaController::class)
            ->only(['store'])
            ->parameters(['dispositivos-marca' => 'dispositivosMarca']);

        Route::resource('instituciones.locales-ie', LocalInstEducController::class)
            ->only(['store', 'destroy'])
            ->shallow()
            ->parameters(['instituciones' => 'institucione', 'locales-ie' => 'localesIe']);

        Route::resource('locales-ie.relojes', RelojController::class)
            ->only(['store', 'update', 'destroy'])
            ->shallow()
            ->parameters(['locales-ie' => 'localesIe', 'relojes' => 'reloje']);

        Route::post('locales-ie/{localesIe}/relojes-masivos', [RelojesMasivaController::class, 'store'])
            ->name('locales-ie.relojes-masivos.store');

        Route::post('marcaciones/cargar-excel', [CargaMarcacionesController::class, 'store'])
            ->name('marcaciones.cargar-excel');

        Route::resource('locales-ie.marcaciones-local', LocalMarcacionController::class)
            ->only(['store', 'destroy'])
            ->shallow()
            ->parameters(['locales-ie' => 'localesIe', 'marcaciones-local' => 'marcacionesLocal']);
    });
    Route::middleware('permiso:infraestructura.eliminar')->group(function () {
        Route::resource('locales', LocalController::class)
            ->only(['destroy'])
            ->parameters(['locales' => 'local']);
        Route::resource('dispositivos-marca', DispositivoMarcaController::class)
            ->only(['destroy'])
            ->parameters(['dispositivos-marca' => 'dispositivosMarca']);
    });

    // ── Horarios ─────────────────────────────────────────────────────────────
    Route::middleware('permiso:horarios.ver')->group(function () {
        Route::resource('horarios-cursos', HorarioCursoController::class)
            ->only(['index'])
            ->parameters(['horarios-cursos' => 'horarioCurso']);

        Route::resource('horarios-trabajador', HorarioTrabajadorController::class)
            ->only(['index', 'show'])
            ->parameters(['horarios-trabajador' => 'horarioTrabajador']);
    });
    Route::middleware('permiso:horarios.crear,horarios.editar')->group(function () {
        Route::resource('horarios-cursos', HorarioCursoController::class)
            ->only(['store', 'update'])
            ->parameters(['horarios-cursos' => 'horarioCurso']);

        Route::post('horarios-masivos', [HorarioMasivoController::class, 'store'])
            ->name('horarios-masivos.store');

        Route::resource('horarios-cursos.cargas', CargaHorariaController::class)
            ->only(['store', 'update', 'destroy'])
            ->shallow()
            ->parameters(['horarios-cursos' => 'horarioCurso', 'cargas' => 'cargaHoraria']);

        // Crear / actualizar horario manual (administrativo) para trabajadores sin cursos
        Route::post('horarios-trabajador/manual', [
            HorarioTrabajadorController::class, 'storeManual',
        ])->name('horarios-trabajador.manual.store');

    });
    Route::middleware('permiso:horarios.eliminar')->group(function () {
        Route::resource('horarios-cursos', HorarioCursoController::class)
            ->only(['destroy'])
            ->parameters(['horarios-cursos' => 'horarioCurso']);
    });

    // ── Trámites: Expedientes ────────────────────────────────────────────────
    Route::middleware('permiso:tramites.ver')->group(function () {
        Route::resource('expedientes', ExpedienteController::class)
            ->only(['index']);

        Route::get('documentos-tram/{documentoTram}/descargar', [ExpedienteController::class, 'descargarDocumento'])
            ->name('documentos-tram.descargar');
    });
    Route::middleware('permiso:tramites.crear')->group(function () {
        Route::resource('expedientes', ExpedienteController::class)
            ->only(['store']);
    });
    Route::middleware('permiso:tramites.anular')->group(function () {
        Route::post('expedientes/{expediente}/anular', [ExpedienteController::class, 'anular'])
            ->name('expedientes.anular');
    });

});

require __DIR__.'/settings.php';
require __DIR__.'/api.php';
