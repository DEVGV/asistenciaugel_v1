<?php

use App\Http\Controllers\Api\MobileController;
use App\Http\Controllers\Api\ParamController;
use App\Http\Controllers\Api\SunatController;
use App\Http\Controllers\Configuracion\UsuarioApiController;
use App\Http\Controllers\Configuracion\ZonaController;
use App\Http\Controllers\Entidad\EntidadController;
use App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController;
use App\Http\Controllers\Persona\PersonaController;
use App\Http\Controllers\Trabajador\TrabajadorController;
use App\Http\Controllers\Tramite\ExpedienteController;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Rutas de la API JSON del sistema. Requieren autenticacion.
|
*/

Route::prefix('api/mobile')
    ->withoutMiddleware([ValidateCsrfToken::class, PreventRequestForgery::class])
    ->name('api.mobile.')
    ->group(function () {
        Route::post('login', [MobileController::class, 'login'])->name('login');
    });

Route::middleware(['auth'])->prefix('api')->group(function () {
    // Parametros (tablas de solo lectura del schema param)
    Route::get('params', [ParamController::class, 'types'])->name('api.params.types');
    Route::get('params/ubigeo/{ubigeo}', [ParamController::class, 'reverseUbigeo'])->name('api.params.ubigeo');
    Route::get('params/{type}', [ParamController::class, 'index'])->name('api.params.index');

    // Consultas SUNAT via apiperu.dev
    Route::post('sunat/ruc', [SunatController::class, 'ruc'])->name('api.sunat.ruc');
    Route::post('sunat/dni', [SunatController::class, 'dni'])->name('api.sunat.dni');

    // Busqueda de Personas
    Route::get('personas/search', [PersonaController::class, 'search'])->name('api.personas.search');

    // Busqueda de Trabajadores
    Route::get('trabajadores/search', [TrabajadorController::class, 'search'])->name('api.trabajadores.search');

    // Usuario de un trabajador (para modal de gestión)
    Route::get('trabajadores/{trabajador}/usuario', [UsuarioApiController::class, 'porTrabajador'])
        ->name('api.trabajadores.usuario');

    // Usuario por su propio ID (para modal desde lista de usuarios)
    Route::get('usuarios/{usuario}/datos', [UsuarioApiController::class, 'porUsuario'])
        ->name('api.usuarios.datos');

    // Permisos directos del usuario por IE
    Route::get('usuarios/{usuario}/permisos-ie', [UsuarioApiController::class, 'permisosIe'])
        ->name('api.usuarios.permisos-ie');
    Route::post('usuarios/{usuario}/permisos-ie', [UsuarioApiController::class, 'syncPermisosIe'])
        ->name('api.usuarios.permisos-ie.sync');

    // Busqueda de Zonas
    Route::get('zonas/search', [ZonaController::class, 'search'])->name('api.zonas.search');

    // Busqueda de Entidades
    Route::get('entidades/search', [EntidadController::class, 'search'])->name('api.entidades.search');

    // Busqueda de Instituciones Educativas (typeahead en formularios)
    Route::get('instituciones/search', [InstitucionEducativaController::class, 'search'])->name('api.instituciones.search');
    Route::get('instituciones/{institucione}/detalles', [InstitucionEducativaController::class, 'detallesJson'])->name('api.instituciones.detalles');

    // Locales de marcación disponibles de una IE (para select en formularios de altas)
    Route::get('instituciones/{institucione}/locales', [InstitucionEducativaController::class, 'locales'])->name('api.instituciones.locales');

    // Trámite: Expedientes (para tabs embebidos en Trabajador e IE)
    Route::get('expedientes/{expediente}/detalle', [ExpedienteController::class, 'detalleJson'])
        ->name('api.expedientes.detalle');
    Route::get('trabajadores/{trabajador}/expedientes', [ExpedienteController::class, 'porTrabajador'])
        ->name('api.trabajadores.expedientes');
    Route::get('trabajadores/{trabajador}/altas-activas', [ExpedienteController::class, 'altasActivas'])
        ->name('api.trabajadores.altas-activas');
    Route::get('instituciones/{institucione}/expedientes', [ExpedienteController::class, 'porInstitucion'])
        ->name('api.instituciones.expedientes');
    Route::get('instituciones/{institucione}/personal-activo', [ExpedienteController::class, 'personalActivo'])
        ->name('api.instituciones.personal-activo');

    // Trámite: Registro directo (sin expediente previo)
    Route::post('registro-directo', [ExpedienteController::class, 'registroDirecto'])
        ->name('api.registro-directo');

    // Trámite: Autorizar / Rechazar / CUD por tipo
    Route::post('expedientes/{expediente}/autorizar', [ExpedienteController::class, 'autorizar'])
        ->name('api.expedientes.autorizar');
    Route::post('expedientes/{expediente}/rechazar', [ExpedienteController::class, 'rechazar'])
        ->name('api.expedientes.rechazar');
    Route::post('expedientes/{expediente}/suspension', [ExpedienteController::class, 'registrarSuspension'])
        ->name('api.expedientes.suspension');
    Route::post('expedientes/{expediente}/justificacion', [ExpedienteController::class, 'registrarJustificacion'])
        ->name('api.expedientes.justificacion');
    Route::post('expedientes/{expediente}/incapacidad', [ExpedienteController::class, 'registrarIncapacidad'])
        ->name('api.expedientes.incapacidad');
    Route::post('expedientes/{expediente}/exoneracion', [ExpedienteController::class, 'registrarExoneracion'])
        ->name('api.expedientes.exoneracion');

    // Motivos filtrados para formularios dinámicos
    Route::get('motivos-suspension', [ExpedienteController::class, 'motivosSuspension'])
        ->name('api.motivos-suspension');
    Route::get('motivos-incapacidad', [ExpedienteController::class, 'motivosIncapacidad'])
        ->name('api.motivos-incapacidad');
    Route::get('motivos-justificacion', [ExpedienteController::class, 'motivosJustificacion'])
        ->name('api.motivos-justificacion');
    Route::get('motivos-exoneracion', [ExpedienteController::class, 'motivosExoneracion'])
        ->name('api.motivos-exoneracion');

    Route::prefix('mobile')
        ->withoutMiddleware([ValidateCsrfToken::class, PreventRequestForgery::class])
        ->name('api.mobile.')
        ->group(function () {
            Route::get('me', [MobileController::class, 'me'])->name('me');
            Route::get('config', [MobileController::class, 'config'])->name('config');
            Route::post('logout', [MobileController::class, 'logout'])->name('logout');
            Route::post('biometria/enrolar-rostro', [MobileController::class, 'enrollFace'])->name('biometria.enroll-face');
            Route::post('biometria/local-device/habilitar', [MobileController::class, 'enableLocalBiometric'])->name('biometria.enable-local');
            Route::post('marcaciones/prevalidar', [MobileController::class, 'prevalidate'])->name('marcaciones.prevalidate');
            Route::post('marcaciones', [MobileController::class, 'storeMark'])->name('marcaciones.store');
            Route::get('marcaciones', [MobileController::class, 'marks'])->name('marcaciones.index');
            Route::get('horario', [MobileController::class, 'schedule'])->name('horario.index');
        });
});
