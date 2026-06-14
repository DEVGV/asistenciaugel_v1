<?php

use App\Models\Conasis\ConasisLocales;
use App\Models\Conasis\ConasisLocalesInstEduc;
use App\Models\InstitucionesEduc;
use App\Models\Param\ParamModalidadesForm;
use App\Models\Param\ParamNivelesCiclo;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;
use function Pest\Laravel\post;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAs($this->user);
    $this->modalidadId = ParamModalidadesForm::first()?->id ?? 1;
    $this->nivelId = ParamNivelesCiclo::first()?->id ?? 1;

    $this->ie = InstitucionesEduc::create([
        'codigoInstitucion' => 'INFRA'.fake()->unique()->numerify('####'),
        'nombreLegal' => 'IE Infra Test',
        'modalidadFormativa_id' => $this->modalidadId,
        'nivelCiclo_id' => $this->nivelId,
        'created_by' => $this->user->id,
    ]);
});

it('can store a new local and associate it to an IE', function () {
    post(route('instituciones.locales-ie.store', $this->ie), [
        'nombre' => 'Local Principal',
        'domicilio' => 'Av. Test 123',
    ])->assertRedirect(route('instituciones.show', $this->ie));

    $localIe = ConasisLocalesInstEduc::where('institucionEduc_id', $this->ie->id)->first();
    expect($localIe)->not->toBeNull();
    expect($localIe->local)->not->toBeNull();
    expect($localIe->local->nombre)->toBe('Local Principal');
});

it('validates nombre is required when creating local', function () {
    post(route('instituciones.locales-ie.store', $this->ie), [])
        ->assertSessionHasErrors(['nombre']);
});

it('can destroy a local-ie association', function () {
    $local = ConasisLocales::create([
        'nombre' => 'Local Delete Test',
        'created_by' => $this->user->id,
        'activo' => true,
    ]);

    $localIe = ConasisLocalesInstEduc::create([
        'local_id' => $local->id,
        'institucionEduc_id' => $this->ie->id,
        'created_by' => $this->user->id,
    ]);

    delete(route('locales-ie.destroy', $localIe))
        ->assertRedirect(route('instituciones.show', $this->ie));

    expect(ConasisLocalesInstEduc::find($localIe->id))->toBeNull();
});
