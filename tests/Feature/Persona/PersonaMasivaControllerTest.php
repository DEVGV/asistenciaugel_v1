<?php

use App\Models\Personas;
use App\Models\User;

test('guests cannot access personas-masivas endpoint', function () {
    $this->postJson(route('personas.masivo.store'), ['filas' => []])
        ->assertUnauthorized();
});

test('authenticated users can bulk register personas', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $doc1 = fake()->numerify('########');
    $doc2 = fake()->numerify('########');

    $filas = [
        [
            'tipoDocIdentidad_id' => 1,
            'docIdentidad' => $doc1,
            'paterno' => 'GARCIA',
            'materno' => 'LOPEZ',
            'nombre' => 'JUAN',
            'sexo_id' => 1,
            'pais_id' => 1,
            'es_trabajador' => false,
        ],
        [
            'tipoDocIdentidad_id' => 1,
            'docIdentidad' => $doc2,
            'paterno' => 'PEREZ',
            'materno' => 'RAMIREZ',
            'nombre' => 'MARIA',
            'sexo_id' => 2,
            'pais_id' => 1,
            'es_trabajador' => false,
        ],
    ];

    $response = $this->postJson(route('personas.masivo.store'), ['filas' => $filas]);

    $response->assertSuccessful();
    $response->assertJson(['insertados' => 2]);

    $this->assertDatabaseHas('t_personas', ['docIdentidad' => $doc1]);
    $this->assertDatabaseHas('t_personas', ['docIdentidad' => $doc2]);
});

test('bulk registration can mark personas as workers', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $doc = fake()->numerify('########');

    $filas = [
        [
            'tipoDocIdentidad_id' => 1,
            'docIdentidad' => $doc,
            'paterno' => 'WORKER',
            'materno' => 'BULK',
            'nombre' => 'PEDRO',
            'sexo_id' => 1,
            'pais_id' => 1,
            'es_trabajador' => true,
        ],
    ];

    $response = $this->postJson(route('personas.masivo.store'), ['filas' => $filas]);

    $response->assertSuccessful();
    $response->assertJson(['insertados' => 1]);

    $persona = Personas::where('docIdentidad', $doc)->first();
    expect($persona)->not->toBeNull();
    expect($persona->trabajador)->not->toBeNull();
    expect($persona->trabajador->activo)->toBeTrue();
    expect($persona->trabajador->user)->not->toBeNull();
    expect($persona->trabajador->user->login)->toBe($doc);
});

test('bulk registration skips duplicate documents and reports errors', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $doc = fake()->numerify('########');

    // Pre-create persona
    Personas::create([
        'tipoDocIdentidad_id' => 1,
        'docIdentidad' => $doc,
        'paterno' => 'EXISTING',
        'materno' => 'TEST',
        'nombre' => 'PERSONA',
        'sexo_id' => 1,
        'pais_id' => 1,
        'created_by' => $user->id,
        'activo' => true,
    ]);

    $filas = [
        [
            'tipoDocIdentidad_id' => 1,
            'docIdentidad' => $doc,
            'paterno' => 'DUPLICATE',
            'materno' => 'TEST',
            'nombre' => 'FAIL',
            'sexo_id' => 1,
            'pais_id' => 1,
            'es_trabajador' => false,
        ],
    ];

    $response = $this->postJson(route('personas.masivo.store'), ['filas' => $filas]);

    $response->assertStatus(422);
    $response->assertJson(['insertados' => 0]);
    expect($response->json('errores_validacion'))->not->toBeEmpty();
});

test('bulk registration validates required fields per row', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $filas = [
        [
            // Missing required fields
            'docIdentidad' => '12345678',
            'paterno' => 'INCOMPLETE',
        ],
    ];

    $response = $this->postJson(route('personas.masivo.store'), ['filas' => $filas]);

    $response->assertStatus(422);
    $response->assertJson(['insertados' => 0]);
});

test('bulk registration requires filas array', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $this->postJson(route('personas.masivo.store'), [])
        ->assertUnprocessable();
});
