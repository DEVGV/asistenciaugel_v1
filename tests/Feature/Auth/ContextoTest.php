<?php

use App\Models\Auth\Perfil;
use App\Models\Auth\UsuarioPerfilIe;
use App\Models\Entidades;
use App\Models\InstitucionesEduc;
use App\Models\Param\ParamModalidadesForm;
use App\Models\Param\ParamNivelesCiclo;
use App\Models\Param\ParamTipoEntidad;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(DatabaseTransactions::class);

function crearUgel(string $nombre): Entidades
{
    $tipoUgel = ParamTipoEntidad::firstOrCreate(
        ['abreviatura' => 'UGEL'],
        ['codigo' => '1', 'nombre' => 'UNIDAD DE GESTION EDUCATIVA LOCAL', 'activo' => true],
    );

    return Entidades::create([
        'tipoEntidad_id' => $tipoUgel->id,
        'razonSocial' => $nombre,
        'activo' => true,
    ]);
}

function crearIe(Entidades $ugel, string $nombre): InstitucionesEduc
{
    return InstitucionesEduc::create([
        'entidadUgel_id' => $ugel->id,
        'nombreLegal' => $nombre,
        'modalidadFormativa_id' => ParamModalidadesForm::first()->id,
        'nivelCiclo_id' => ParamNivelesCiclo::first()->id,
    ]);
}

function asignarPerfilIe(User $user, ?InstitucionesEduc $ie, ?Entidades $ugel = null): UsuarioPerfilIe
{
    $perfil = Perfil::firstOrCreate(['nombre' => 'Docente Test'], ['activo' => true]);

    return UsuarioPerfilIe::create([
        'user_id' => $user->id,
        'perfil_id' => $perfil->id,
        'entidadUgel_id' => $ugel?->id ?? $ie?->entidadUgel_id,
        'institucionEducativa_id' => $ie?->id,
        'activo' => true,
    ]);
}

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAs($this->user);
});

it('permite acceder al dashboard sin asignaciones', function () {
    get(route('dashboard'))->assertOk();
});

it('establece el contexto automáticamente con una sola IE asignada', function () {
    $ugel = crearUgel('UGEL TEST AUTO');
    $ie = crearIe($ugel, 'IE TEST AUTO');
    asignarPerfilIe($this->user, $ie);

    get(route('dashboard'))->assertOk();

    expect(session('contexto.ugel_id'))->toBe($ugel->id)
        ->and(session('contexto.ie_id'))->toBe($ie->id);
});

it('redirige a la selección de contexto cuando hay varias IEs', function () {
    $ugel = crearUgel('UGEL TEST MULTI');
    asignarPerfilIe($this->user, crearIe($ugel, 'IE UNO'));
    asignarPerfilIe($this->user, crearIe($ugel, 'IE DOS'));

    get(route('dashboard'))->assertRedirect(route('contexto.seleccionar'));
});

it('muestra la pantalla de selección con las opciones del usuario', function () {
    $ugel = crearUgel('UGEL TEST PANTALLA');
    asignarPerfilIe($this->user, crearIe($ugel, 'IE UNO'));
    asignarPerfilIe($this->user, crearIe($ugel, 'IE DOS'));

    get(route('contexto.seleccionar'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('auth/SeleccionarContexto')
            ->has('opciones', 1)
            ->has('opciones.0.ies', 2));
});

it('permite establecer un contexto válido', function () {
    $ugel = crearUgel('UGEL TEST POST');
    $ie = crearIe($ugel, 'IE ELEGIDA');
    asignarPerfilIe($this->user, $ie);
    asignarPerfilIe($this->user, crearIe($ugel, 'IE OTRA'));

    post(route('contexto.establecer'), [
        'ugel_id' => $ugel->id,
        'ie_id' => $ie->id,
    ])->assertRedirect(route('dashboard'));

    expect(session('contexto.ugel_id'))->toBe($ugel->id)
        ->and(session('contexto.ie_id'))->toBe($ie->id);
});

it('rechaza un contexto de una IE no asignada', function () {
    $ugel = crearUgel('UGEL TEST FORBIDDEN');
    asignarPerfilIe($this->user, crearIe($ugel, 'IE PROPIA'));
    asignarPerfilIe($this->user, crearIe($ugel, 'IE PROPIA 2'));

    $ieAjena = crearIe(crearUgel('UGEL AJENA'), 'IE AJENA');

    post(route('contexto.establecer'), [
        'ugel_id' => $ieAjena->entidadUgel_id,
        'ie_id' => $ieAjena->id,
    ])->assertForbidden();
});

it('permite al admin de UGEL trabajar con todas las IEs', function () {
    $ugel = crearUgel('UGEL TEST ADMIN');
    crearIe($ugel, 'IE A');
    crearIe($ugel, 'IE B');
    asignarPerfilIe($this->user, null, $ugel);

    // Admin de una sola UGEL → contexto automático con todas las IEs
    get(route('dashboard'))->assertOk();

    expect(session('contexto.ugel_id'))->toBe($ugel->id)
        ->and(session('contexto.ie_id'))->toBeNull();
});

it('el admin multi-UGEL debe seleccionar la UGEL de trabajo', function () {
    $ugelA = crearUgel('UGEL TEST A');
    $ugelB = crearUgel('UGEL TEST B');
    asignarPerfilIe($this->user, null, $ugelA);
    asignarPerfilIe($this->user, null, $ugelB);

    get(route('dashboard'))->assertRedirect(route('contexto.seleccionar'));

    post(route('contexto.establecer'), [
        'ugel_id' => $ugelB->id,
        'ie_id' => null,
    ])->assertRedirect(route('dashboard'));

    expect(session('contexto.ugel_id'))->toBe($ugelB->id);
});
