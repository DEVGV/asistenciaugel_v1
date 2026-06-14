<?php

use App\Models\Personas;
use App\Models\Trabajador;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAs($this->user);
});

it('can list trabajadores', function () {
    get(route('trabajadores.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('trabajador/Index'));
});

it('can store new trabajadores in bulk', function () {
    $persona1 = Personas::create([
        'tipoDocIdentidad_id' => 1,
        'docIdentidad' => fake()->unique()->numerify('########'),
        'paterno' => 'PATERNO1',
        'materno' => 'MATERNO1',
        'nombre' => 'NOMBRE1',
        'sexo_id' => 1,
        'pais_id' => 1,
        'created_by' => $this->user->id,
        'activo' => true,
    ]);

    post(route('trabajadores.store'), [
        'trabajadores' => [
            [
                'persona_id' => $persona1->id,
                'activo' => true,
            ],
        ],
    ])->assertRedirect(route('trabajadores.index'));

    $trabajador = Trabajador::where('persona_id', $persona1->id)->first();
    expect($trabajador)->not->toBeNull();
    expect($trabajador->codigo)->toStartWith('TRA');
});

it('validates required fields on store', function () {
    post(route('trabajadores.store'), [])
        ->assertSessionHasErrors(['trabajadores']);
});

it('can visit the show detail page', function () {
    $persona = Personas::create([
        'tipoDocIdentidad_id' => 1,
        'docIdentidad' => fake()->unique()->numerify('########'),
        'paterno' => 'SHOW',
        'materno' => 'TEST',
        'nombre' => 'WORKER',
        'sexo_id' => 1,
        'pais_id' => 1,
        'created_by' => $this->user->id,
        'activo' => true,
    ]);

    $trabajador = Trabajador::create([
        'persona_id' => $persona->id,
        'created_by' => $this->user->id,
        'activo' => true,
    ]);

    expect($trabajador->codigo)->toStartWith('TRA');

    get(route('trabajadores.show', $trabajador))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('trabajador/Show'));
});

it('can deactivate a trabajador', function () {
    $persona = Personas::create([
        'tipoDocIdentidad_id' => 1,
        'docIdentidad' => fake()->unique()->numerify('########'),
        'paterno' => 'DELETE',
        'materno' => 'TEST',
        'nombre' => 'WORKER',
        'sexo_id' => 1,
        'pais_id' => 1,
        'created_by' => $this->user->id,
        'activo' => true,
    ]);

    $trabajador = Trabajador::create([
        'persona_id' => $persona->id,
        'created_by' => $this->user->id,
        'activo' => true,
    ]);

    delete(route('trabajadores.destroy', $trabajador))
        ->assertRedirect(route('trabajadores.index'));

    expect($trabajador->fresh()->activo)->toBeFalse();
});

it('auto-generates unique sequential codes for trabajadores', function () {
    $persona1 = Personas::create([
        'tipoDocIdentidad_id' => 1,
        'docIdentidad' => fake()->unique()->numerify('########'),
        'paterno' => 'CODE1', 'materno' => 'TEST', 'nombre' => 'A',
        'sexo_id' => 1, 'pais_id' => 1,
        'created_by' => $this->user->id, 'activo' => true,
    ]);
    $persona2 = Personas::create([
        'tipoDocIdentidad_id' => 1,
        'docIdentidad' => fake()->unique()->numerify('########'),
        'paterno' => 'CODE2', 'materno' => 'TEST', 'nombre' => 'B',
        'sexo_id' => 1, 'pais_id' => 1,
        'created_by' => $this->user->id, 'activo' => true,
    ]);

    $t1 = Trabajador::create(['persona_id' => $persona1->id, 'created_by' => $this->user->id, 'activo' => true]);
    $t2 = Trabajador::create(['persona_id' => $persona2->id, 'created_by' => $this->user->id, 'activo' => true]);

    expect($t1->codigo)->toStartWith('TRA');
    expect($t2->codigo)->toStartWith('TRA');
    expect($t1->codigo)->not->toBe($t2->codigo);
});
