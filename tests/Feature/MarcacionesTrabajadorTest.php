<?php

use App\Models\Conasis\ConasisMarcaciones;
use App\Models\Trabajador;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(DatabaseTransactions::class);

/**
 * Usamos el trabajador real del docente (id=9) que ya existe en la base de datos.
 * El test es transaccional: cualquier marcación creada se revierte al finalizar.
 */
beforeEach(function () {
    $this->user = User::factory()->create();
    actingAs($this->user);

    // Establecer contexto de IE en sesión (requerido por middleware EnsureContextoSeleccionado)
    // IE id=7 es la institución donde está dado de alta el docente trabajador_id=9
    session(['contexto.ugel_id' => 1, 'contexto.ie_id' => 7]);

    // Trabajador real: Keyla Lisbeth Rojas, código TRA76955034
    $this->trabajador = Trabajador::findOrFail(9);
    $this->altaId = 13; // alta activa en IE id=7
});

test('returns json with marcaciones structure for authenticated user', function () {
    ConasisMarcaciones::insert([
        [
            'trabajador_id' => $this->trabajador->id,
            'altaTrabajador_id' => $this->altaId,
            'tipo' => 'E',
            'medioMarcacion' => 'M',
            'fechaMarcacion' => '2026-06-02 08:05:00',
            'fechaRegistro' => '2026-06-02 08:05:00',
            'procesado' => true,
            'created_by' => 1,
            'created_at' => now()->toDateTimeString(),
        ],
        [
            'trabajador_id' => $this->trabajador->id,
            'altaTrabajador_id' => $this->altaId,
            'tipo' => 'S',
            'medioMarcacion' => 'M',
            'fechaMarcacion' => '2026-06-02 11:30:00',
            'fechaRegistro' => '2026-06-02 11:30:00',
            'procesado' => true,
            'created_by' => 1,
            'created_at' => now()->toDateTimeString(),
        ],
    ]);

    $response = get("/trabajadores/{$this->trabajador->id}/marcaciones?anio=2026&mes=6");

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'codigo', 'fechaMarcacion', 'tipo', 'medioMarcacion', 'procesado'],
            ],
        ]);

    // Al menos las 2 que acabamos de insertar deben estar presentes
    expect($response->json('data'))->not->toBeEmpty();
});

test('filters marcaciones by month and year', function () {
    // Insertamos una marca en agosto (mes que no tiene datos del seeder)
    ConasisMarcaciones::insert([
        [
            'trabajador_id' => $this->trabajador->id,
            'tipo' => 'E',
            'medioMarcacion' => 'M',
            'fechaMarcacion' => '2026-08-04 08:00:00',
            'fechaRegistro' => '2026-08-04 08:00:00',
            'procesado' => true,
            'created_by' => 1,
            'created_at' => now()->toDateTimeString(),
        ],
    ]);

    // Agosto solo tiene la que acabamos de insertar
    $response = get("/trabajadores/{$this->trabajador->id}/marcaciones?anio=2026&mes=8");
    $response->assertOk()->assertJsonCount(1, 'data');

    // Septiembre no tiene ninguna
    $response = get("/trabajadores/{$this->trabajador->id}/marcaciones?anio=2026&mes=9");
    $response->assertOk()->assertJson(['data' => []]);
});

test('returns empty data when no marcaciones exist for month', function () {
    $response = get("/trabajadores/{$this->trabajador->id}/marcaciones?anio=2025&mes=1");

    $response->assertOk()
        ->assertJson(['data' => []]);
});

test('unauthenticated user is redirected from marcaciones endpoint', function () {
    auth()->logout();

    $response = get("/trabajadores/{$this->trabajador->id}/marcaciones");
    $response->assertRedirect();
});
