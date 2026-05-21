<?php

use App\Http\Controllers\Api\ParamController;
use App\Http\Controllers\Api\SunatController;
use App\Http\Controllers\Configuracion\ZonaController;
use App\Http\Controllers\Entidad\EntidadController;
use App\Http\Controllers\Persona\PersonaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Rutas de la API JSON del sistema. Requieren autenticación.
|
*/

Route::middleware(['auth'])->prefix('api')->group(function () {
    // Parámetros (tablas de solo lectura del schema param)
    Route::get('params', [ParamController::class, 'types'])->name('api.params.types');
    Route::get('params/ubigeo/{ubigeo}', [ParamController::class, 'reverseUbigeo'])->name('api.params.ubigeo');
    Route::get('params/{type}', [ParamController::class, 'index'])->name('api.params.index');

    // Consultas SUNAT via apiperu.dev
    Route::post('sunat/ruc', [SunatController::class, 'ruc'])->name('api.sunat.ruc');
    Route::post('sunat/dni', [SunatController::class, 'dni'])->name('api.sunat.dni');

    // Búsqueda de Personas
    Route::get('personas/search', [PersonaController::class, 'search'])->name('api.personas.search');

    // Búsqueda de Zonas
    Route::get('zonas/search', [ZonaController::class, 'search'])->name('api.zonas.search');

    // Búsqueda de Entidades
    Route::get('entidades/search', [EntidadController::class, 'search'])->name('api.entidades.search');
});
