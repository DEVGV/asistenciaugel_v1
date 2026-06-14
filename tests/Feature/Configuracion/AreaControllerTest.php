<?php

use App\Models\Areas;
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
});

it('can list areas', function () {
    get(route('areas.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('configuracion/areas/Index'));
});

it('can create an area', function () {
    $nombre = 'Área Test '.uniqid();

    post(route('areas.store'), [
        'nombre' => $nombre,
        'sigla' => 'TI',
        'activo' => true,
    ])->assertRedirect(route('areas.index'));

    $area = Areas::where('nombre', $nombre)->first();
    expect($area)->not->toBeNull();
    expect($area->codigo)->toStartWith('ARE');
});

it('validates required fields on store', function () {
    post(route('areas.store'), [])
        ->assertSessionHasErrors(['nombre']);
});

it('can update an area', function () {
    $area = Areas::factory()->create(['nombre' => 'Original '.uniqid()]);

    put(route('areas.update', $area), [
        'nombre' => 'Actualizado',
        'activo' => true,
    ])->assertRedirect(route('areas.index'));

    expect($area->fresh()->nombre)->toBe('Actualizado');
});

it('can deactivate an area', function () {
    $area = Areas::factory()->create(['activo' => true]);

    delete(route('areas.destroy', $area))
        ->assertRedirect(route('areas.index'));

    expect($area->fresh()->activo)->toBeFalse();
});

it('auto-generates unique sequential codes', function () {
    $area1 = Areas::factory()->create();
    $area2 = Areas::factory()->create();

    expect($area1->codigo)->toStartWith('ARE');
    expect($area2->codigo)->toStartWith('ARE');
    expect($area1->codigo)->not->toBe($area2->codigo);
});
