<?php

use App\Models\Conasis\ConasisLocales;
use App\Models\Conasis\ConasisLocalesInstEduc;
use App\Models\Conasis\ConasisRelojes;
use App\Models\InstitucionesEduc;
use App\Models\Param\ParamModalidadesForm;
use App\Models\Param\ParamNivelesCiclo;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAs($this->user);

    $modalidadId = ParamModalidadesForm::first()?->id ?? 1;
    $nivelId = ParamNivelesCiclo::first()?->id ?? 1;

    $ie = InstitucionesEduc::create([
        'codigoInstitucion' => 'REL'.fake()->unique()->numerify('####'),
        'nombreLegal' => 'IE Reloj Test',
        'modalidadFormativa_id' => $modalidadId,
        'nivelCiclo_id' => $nivelId,
        'created_by' => $this->user->id,
    ]);

    $local = ConasisLocales::create([
        'nombre' => 'Local Reloj',
        'created_by' => $this->user->id,
        'activo' => true,
    ]);

    $this->localIe = ConasisLocalesInstEduc::create([
        'local_id' => $local->id,
        'institucionEduc_id' => $ie->id,
        'created_by' => $this->user->id,
    ]);
});

it('can store a new reloj for a localInstEduc', function () {
    post(route('locales-ie.relojes.store', $this->localIe), [
        'nombre' => 'Reloj Principal',
        'dreccionIP' => '192.168.1.100',
        'puerto' => 4370,
    ])->assertRedirect();

    expect(ConasisRelojes::where('localInstEduc_id', $this->localIe->id)->count())->toBe(1);
});

it('can update a reloj', function () {
    $reloj = ConasisRelojes::create([
        'nombre' => 'Reloj Edit',
        'localInstEduc_id' => $this->localIe->id,
        'created_by' => $this->user->id,
        'activo' => true,
    ]);

    put(route('relojes.update', $reloj), [
        'nombre' => 'Reloj Editado',
        'dreccionIP' => '10.0.0.1',
    ])->assertRedirect();

    expect($reloj->fresh()->nombre)->toBe('Reloj Editado');
});

it('can soft-delete a reloj', function () {
    $reloj = ConasisRelojes::create([
        'nombre' => 'Reloj Delete',
        'localInstEduc_id' => $this->localIe->id,
        'created_by' => $this->user->id,
        'activo' => true,
    ]);

    delete(route('relojes.destroy', $reloj))->assertRedirect();

    expect($reloj->fresh()->activo)->toBeFalse();
});
