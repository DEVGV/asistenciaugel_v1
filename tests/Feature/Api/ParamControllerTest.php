<?php

use App\Models\User;

test('guests cannot access param types', function () {
    $this->getJson(route('api.params.types'))
        ->assertUnauthorized();
});

test('guests cannot access param data', function () {
    $this->getJson(route('api.params.index', ['type' => 'sexos']))
        ->assertUnauthorized();
});

test('authenticated users can list available param types', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->getJson(route('api.params.types'));

    $response->assertSuccessful()
        ->assertJsonStructure(['data'])
        ->assertJsonFragment(['sexos'])
        ->assertJsonFragment(['paises'])
        ->assertJsonFragment(['tipos-doc-identidad']);
});

test('authenticated users can fetch param data', function (string $type) {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->getJson(route('api.params.index', ['type' => $type]));

    $response->assertSuccessful()
        ->assertJsonStructure(['data']);
})->with([
    'sexos',
    'paises',
    'tipos-doc-identidad',
    'tipos-zona',
    'regimenes-laborales',
    'tipos-trabajador',
    'tipos-contrato',
    'situaciones-laborales',
    'motivos-baja',
    'roles-trabajador',
    'turnos',
    'estados-asistencia',
    'estados-tramite',
    'operadores',
    'feriados',
    'documentos',
    'regimen-educ',
    'tipo-inst-educ',
    'modalidades-form',
    'niveles-ciclo',
    'tipo-susp-laboral',
    'tipo-entidad',
    'motivos-susp-lab',
]);

test('invalid param type returns 404', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->getJson(route('api.params.index', ['type' => 'no-existe']));

    $response->assertNotFound()
        ->assertJsonStructure(['message', 'tipos_disponibles']);
});

test('hierarchical params accept parent_id filter', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->getJson(route('api.params.index', [
        'type' => 'departamentos',
        'parent_id' => 1,
    ]));

    $response->assertSuccessful()
        ->assertJsonStructure(['data']);
});

test('hierarchical params work without parent_id', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->getJson(route('api.params.index', ['type' => 'departamentos']));

    $response->assertSuccessful()
        ->assertJsonStructure(['data']);
});
