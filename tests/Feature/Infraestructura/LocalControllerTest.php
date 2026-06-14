<?php

use App\Models\Conasis\ConasisLocales;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAs($this->user);
});

it('can list locales', function () {
    get(route('locales.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('infraestructura/locales/Index'));
});

it('can store a new local', function () {
    $this->from(route('locales.index'))
        ->post(route('locales.store'), [
            'nombre' => 'Local Test '.fake()->word(),
        ])->assertRedirect(route('locales.index'));

    expect(ConasisLocales::where('activo', true)->count())->toBeGreaterThan(0);
});

it('validates nombre is required on store', function () {
    post(route('locales.store'), [])
        ->assertSessionHasErrors(['nombre']);
});

it('can update a local', function () {
    $local = ConasisLocales::create([
        'nombre' => 'Local Update',
        'created_by' => $this->user->id,
        'activo' => true,
    ]);

    $this->from(route('locales.index'))
        ->put(route('locales.update', $local), [
            'nombre' => 'Local Updated',
        ])->assertRedirect(route('locales.index'));

    expect($local->fresh()->nombre)->toBe('Local Updated');
});

it('can soft-delete a local', function () {
    $local = ConasisLocales::create([
        'nombre' => 'Local Delete',
        'created_by' => $this->user->id,
        'activo' => true,
    ]);

    $this->from(route('locales.index'))
        ->delete(route('locales.destroy', $local))
        ->assertRedirect(route('locales.index'));

    expect($local->fresh()->activo)->toBeFalse();
});
