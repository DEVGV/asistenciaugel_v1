<?php

use App\Models\Personas;
use App\Models\User;

test('guests cannot access personas', function () {
    $this->get(route('personas.index'))
        ->assertRedirect(route('login'));
});

test('authenticated users can visit the personas index', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('personas.index'));
    $response->assertSuccessful();
});

test('authenticated users can create a persona', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $doc = fake()->numerify('########');
    $data = [
        'tipoDocIdentidad_id' => 1,
        'docIdentidad' => $doc,
        'paterno' => 'GARCIA',
        'materno' => 'LOPEZ',
        'nombre' => 'JUAN',
        'sexo_id' => 1,
        'pais_id' => 1,
        'activo' => true,
    ];

    $response = $this->post(route('personas.store'), $data);

    $response->assertRedirect(route('personas.index'));
    $this->assertDatabaseHas('t_personas', [
        'docIdentidad' => $doc,
        'paterno' => 'GARCIA',
    ]);
});

test('authenticated users can update a persona', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $doc = fake()->numerify('########');
    $persona = Personas::create([
        'tipoDocIdentidad_id' => 1,
        'docIdentidad' => $doc,
        'paterno' => 'OLD',
        'materno' => 'OLD',
        'nombre' => 'OLD',
        'sexo_id' => 1,
        'pais_id' => 1,
        'created_by' => $user->id,
        'activo' => true,
    ]);

    $data = [
        'tipoDocIdentidad_id' => 1,
        'docIdentidad' => $doc,
        'paterno' => 'NEW',
        'materno' => 'NEW',
        'nombre' => 'NEW',
        'sexo_id' => 1,
        'pais_id' => 1,
        'activo' => true,
    ];

    $response = $this->put(route('personas.update', $persona), $data);

    $response->assertRedirect(route('personas.index'));
    $this->assertDatabaseHas('t_personas', [
        'id' => $persona->id,
        'paterno' => 'NEW',
    ]);
});

test('authenticated users can soft delete a persona', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $persona = Personas::create([
        'tipoDocIdentidad_id' => 1,
        'docIdentidad' => fake()->numerify('########'),
        'paterno' => 'DELETE',
        'materno' => 'TEST',
        'nombre' => 'ME',
        'sexo_id' => 1,
        'pais_id' => 1,
        'created_by' => $user->id,
        'activo' => true,
    ]);

    $response = $this->delete(route('personas.destroy', $persona));

    $response->assertRedirect(route('personas.index'));
    $this->assertDatabaseHas('t_personas', [
        'id' => $persona->id,
        'activo' => false,
    ]);
});

test('authenticated users can view persona detail', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $persona = Personas::create([
        'tipoDocIdentidad_id' => 1,
        'docIdentidad' => fake()->numerify('########'),
        'paterno' => 'SHOW',
        'materno' => 'TEST',
        'nombre' => 'DETAIL',
        'sexo_id' => 1,
        'pais_id' => 1,
        'created_by' => $user->id,
        'activo' => true,
    ]);

    $response = $this->get(route('personas.show', $persona));
    $response->assertSuccessful();
});

test('authenticated users can create a persona and mark them as worker', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $doc = fake()->numerify('########');
    $data = [
        'tipoDocIdentidad_id' => 1,
        'docIdentidad' => $doc,
        'paterno' => 'WORKER',
        'materno' => 'TEST',
        'nombre' => 'JUAN',
        'sexo_id' => 1,
        'pais_id' => 1,
        'activo' => true,
        'es_trabajador' => true,
    ];

    $response = $this->post(route('personas.store'), $data);

    $response->assertRedirect(route('personas.index'));
    $this->assertDatabaseHas('t_personas', [
        'docIdentidad' => $doc,
        'paterno' => 'WORKER',
    ]);

    $persona = Personas::where('docIdentidad', $doc)->first();

    $this->assertDatabaseHas('t_trabajador', [
        'persona_id' => $persona->id,
        'activo' => true,
    ]);

    expect($persona->trabajador)->not->toBeNull();
    expect($persona->trabajador->user)->not->toBeNull();
    expect($persona->trabajador->user->login)->toBe($doc);
});

test('authenticated users can convert an existing persona to a worker', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $doc = fake()->numerify('########');
    $persona = Personas::create([
        'tipoDocIdentidad_id' => 1,
        'docIdentidad' => $doc,
        'paterno' => 'TO_CONVERT',
        'materno' => 'TEST',
        'nombre' => 'JUAN',
        'sexo_id' => 1,
        'pais_id' => 1,
        'created_by' => $user->id,
        'activo' => true,
    ]);

    // load relation explicitly
    $persona->load('trabajador');
    expect($persona->trabajador)->toBeNull();

    $response = $this->post(route('personas.convertir-trabajador', $persona));

    $response->assertRedirect(route('personas.index'));

    $persona->refresh();
    expect($persona->trabajador)->not->toBeNull();
    expect($persona->trabajador->activo)->toBeTrue();
    expect($persona->trabajador->user)->not->toBeNull();
    expect($persona->trabajador->user->login)->toBe($doc);
});
