<?php

use App\Models\User;
use App\Models\Zonas;
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

it('can list zonas', function () {
    get(route('zonas.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('configuracion/zonas/Index'));
});

it('can create a zona', function () {
    $nombre = 'Zona Test '.uniqid();

    post(route('zonas.store'), [
        'nombre' => $nombre,
        'abreviatura' => 'ZT',
        'tipoZona_id' => 1,
        'distrito_id' => null,
        'activo' => true,
    ])->assertRedirect(route('zonas.index'));

    expect(Zonas::where('nombre', $nombre)->exists())->toBeTrue();
});

it('validates required fields on store', function () {
    post(route('zonas.store'), [])
        ->assertSessionHasErrors(['nombre', 'tipoZona_id']);
});

it('can update a zona', function () {
    $zona = Zonas::factory()->create(['nombre' => 'Original '.uniqid()]);

    put(route('zonas.update', $zona), [
        'nombre' => 'Zona Actualizada',
        'abreviatura' => 'ZA',
        'tipoZona_id' => 1,
        'distrito_id' => null,
        'activo' => true,
    ])->assertRedirect(route('zonas.index'));

    expect($zona->fresh()->nombre)->toBe('Zona Actualizada');
});

it('can deactivate a zona', function () {
    $zona = Zonas::factory()->create(['activo' => true]);

    delete(route('zonas.destroy', $zona))
        ->assertRedirect(route('zonas.index'));

    expect($zona->fresh()->activo)->toBeFalse();
});
