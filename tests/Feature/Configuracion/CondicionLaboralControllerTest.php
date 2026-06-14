<?php

use App\Models\CondicionesLaborales;
use App\Models\Param\ParamRegimenLaboral;
use App\Models\Param\ParamTipoTrabajador;
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
    $this->regimen = ParamRegimenLaboral::first();
    $this->tipoTrabajador = ParamTipoTrabajador::first();
});

it('can list condiciones laborales', function () {
    get(route('condiciones-laborales.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('configuracion/condiciones-laborales/Index'));
});

it('can create a condicion laboral', function () {
    $nombre = 'Condición Test '.uniqid();

    post(route('condiciones-laborales.store'), [
        'nombre' => $nombre,
        'codigo' => 'CL-001',
        'abreviatura' => 'CAS',
        'regimenLaboral_id' => $this->regimen->id,
        'tipoTrabajador_id' => $this->tipoTrabajador->id,
    ])->assertRedirect(route('condiciones-laborales.index'));

    expect(CondicionesLaborales::where('nombre', $nombre)->exists())->toBeTrue();
});

it('validates required fields on store', function () {
    post(route('condiciones-laborales.store'), [])
        ->assertSessionHasErrors(['nombre', 'regimenLaboral_id', 'tipoTrabajador_id']);
});

it('can update a condicion laboral', function () {
    $condicion = CondicionesLaborales::factory()->create([
        'regimenLaboral_id' => $this->regimen->id,
        'tipoTrabajador_id' => $this->tipoTrabajador->id,
    ]);

    put(route('condiciones-laborales.update', $condicion), [
        'nombre' => 'Actualizada',
        'regimenLaboral_id' => $this->regimen->id,
        'tipoTrabajador_id' => $this->tipoTrabajador->id,
    ])->assertRedirect(route('condiciones-laborales.index'));

    expect($condicion->fresh()->nombre)->toBe('Actualizada');
});

it('can delete a condicion laboral', function () {
    $condicion = CondicionesLaborales::factory()->create([
        'regimenLaboral_id' => $this->regimen->id,
        'tipoTrabajador_id' => $this->tipoTrabajador->id,
    ]);
    $id = $condicion->id;

    delete(route('condiciones-laborales.destroy', $condicion))
        ->assertRedirect(route('condiciones-laborales.index'));

    expect(CondicionesLaborales::find($id))->toBeNull();
});
