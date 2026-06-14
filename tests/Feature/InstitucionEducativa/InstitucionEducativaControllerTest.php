<?php

use App\Models\InstitucionesEduc;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAs($this->user);
    $this->modalidadId = \App\Models\Param\ParamModalidadesForm::first()?->id ?? 1;
    $this->nivelId = \App\Models\Param\ParamNivelesCiclo::first()?->id ?? 1;
});

it('can list instituciones educativas', function () {
    get(route('instituciones.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('institucion-educativa/Index'));
});

it('can store a new institucion educativa', function () {
    post(route('instituciones.store'), [
        'codigoInstitucion' => 'IE' . fake()->unique()->numerify('######'),
        'nombreLegal' => 'IE Test ' . fake()->word(),
        'modalidadFormativa_id' => $this->modalidadId,
        'nivelCiclo_id' => $this->nivelId,
    ])->assertRedirect(route('instituciones.index'));

    expect(InstitucionesEduc::count())->toBeGreaterThan(0);
});

it('validates required fields on store', function () {
    post(route('instituciones.store'), [])
        ->assertSessionHasErrors(['codigoInstitucion', 'nombreLegal', 'modalidadFormativa_id', 'nivelCiclo_id']);
});

it('can show an institucion educativa', function () {
    $ie = InstitucionesEduc::create([
        'codigoInstitucion' => 'SHOW' . fake()->unique()->numerify('####'),
        'nombreLegal' => 'IE Show Test',
        'modalidadFormativa_id' => $this->modalidadId,
        'nivelCiclo_id' => $this->nivelId,
        'created_by' => $this->user->id,
    ]);

    get(route('instituciones.show', $ie))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('institucion-educativa/Show'));
});

it('can update an institucion educativa', function () {
    $ie = InstitucionesEduc::create([
        'codigoInstitucion' => 'UPD' . fake()->unique()->numerify('####'),
        'nombreLegal' => 'IE Update Test',
        'modalidadFormativa_id' => $this->modalidadId,
        'nivelCiclo_id' => $this->nivelId,
        'created_by' => $this->user->id,
    ]);

    put(route('instituciones.update', $ie), [
        'codigoInstitucion' => $ie->codigoInstitucion,
        'nombreLegal' => 'IE Updated Name',
        'modalidadFormativa_id' => $this->modalidadId,
        'nivelCiclo_id' => $this->nivelId,
    ])->assertRedirect(route('instituciones.index'));

    expect($ie->fresh()->nombreLegal)->toBe('IE Updated Name');
});

it('can delete an institucion educativa', function () {
    $ie = InstitucionesEduc::create([
        'codigoInstitucion' => 'DEL' . fake()->unique()->numerify('####'),
        'nombreLegal' => 'IE Delete Test',
        'modalidadFormativa_id' => $this->modalidadId,
        'nivelCiclo_id' => $this->nivelId,
        'created_by' => $this->user->id,
    ]);

    delete(route('instituciones.destroy', $ie))
        ->assertRedirect(route('instituciones.index'));

    expect(InstitucionesEduc::find($ie->id))->toBeNull();
});

it('validates unique codigoInstitucion on store', function () {
    InstitucionesEduc::create([
        'codigoInstitucion' => 'UNIQUE001',
        'nombreLegal' => 'IE Unique',
        'modalidadFormativa_id' => $this->modalidadId,
        'nivelCiclo_id' => $this->nivelId,
        'created_by' => $this->user->id,
    ]);

    post(route('instituciones.store'), [
        'codigoInstitucion' => 'UNIQUE001',
        'nombreLegal' => 'IE Duplicate',
        'modalidadFormativa_id' => $this->modalidadId,
        'nivelCiclo_id' => $this->nivelId,
    ])->assertSessionHasErrors(['codigoInstitucion']);
});
