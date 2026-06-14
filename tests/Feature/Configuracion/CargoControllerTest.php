<?php

use App\Models\Cargos;
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

it('can list cargos', function () {
    get(route('cargos.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('configuracion/cargos/Index'));
});

it('can create a cargo', function () {
    $nombre = 'Cargo Test '.uniqid();

    post(route('cargos.store'), [
        'nombre' => $nombre,
        'codigo' => 'CARGO-01',
        'abreviatura' => 'DIR',
        'activo' => true,
    ])->assertRedirect(route('cargos.index'));

    expect(Cargos::where('nombre', $nombre)->exists())->toBeTrue();
});

it('validates required fields on store', function () {
    post(route('cargos.store'), [])
        ->assertSessionHasErrors(['nombre']);
});

it('can update a cargo', function () {
    $cargo = Cargos::factory()->create(['nombre' => 'Original '.uniqid()]);

    put(route('cargos.update', $cargo), [
        'nombre' => 'Actualizado',
        'activo' => true,
    ])->assertRedirect(route('cargos.index'));

    expect($cargo->fresh()->nombre)->toBe('Actualizado');
});

it('can deactivate a cargo', function () {
    $cargo = Cargos::factory()->create(['activo' => true]);

    delete(route('cargos.destroy', $cargo))
        ->assertRedirect(route('cargos.index'));

    expect($cargo->fresh()->activo)->toBeFalse();
});
