<?php

use App\Models\AltasTrabajadores;
use App\Models\Conasis\ConasisExoneracionesMarcacion;
use App\Models\Conasis\ConasisExpediente;
use App\Models\Conasis\ConasisJustificaciones;
use App\Models\Param\ParamDocumentos;
use App\Models\Param\ParamEstadosTram;
use App\Models\Personas;
use App\Models\Trabajador;
use App\Models\User;
use Database\Seeders\ParamTramiteSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\get;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;
use function Pest\Laravel\seed;

uses(DatabaseTransactions::class);

beforeEach(function () {
    Storage::fake();

    $this->user = User::factory()->create();
    actingAs($this->user);

    seed(ParamTramiteSeeder::class);

    // IDs reales existentes para evitar violaciones de FK
    $this->ieId = DB::table('t_institucionesEduc')->value('id') ?? 1;
    $this->condId = DB::table('t_condicionesLaborales')->value('id') ?? 1;
    $this->tipoContratoId = DB::table('param.t12_tipoContrato')->value('id') ?? 1;
    $this->rolId = DB::table('param.t00_rolTrabajador')->value('id') ?? 1;
    $this->situacionId = DB::table('param.t15_situacionLaboral')->value('id') ?? 1;
    $this->documentoId = ParamDocumentos::where('codigo', 'PAP')->value('id');

    $this->persona = Personas::create([
        'tipoDocIdentidad_id' => 1,
        'docIdentidad' => fake()->unique()->numerify('########'),
        'paterno' => 'PERMISO',
        'materno' => 'TEST',
        'nombre' => 'WORKER',
        'sexo_id' => 1,
        'pais_id' => 1,
        'created_by' => 1,
        'activo' => true,
    ]);

    $this->trabajador = Trabajador::create([
        'persona_id' => $this->persona->id,
        'created_by' => $this->user->id,
        'activo' => true,
    ]);

    $this->alta = AltasTrabajadores::create([
        'trabajador_id' => $this->trabajador->id,
        'institucionEducativa_id' => $this->ieId,
        'condicionLaboral_id' => $this->condId,
        'tipoContrato_id' => $this->tipoContratoId,
        'rolTrabajador_id' => $this->rolId,
        'situacionLaboral_id' => $this->situacionId,
        'fechaInicio' => '2026-01-01',
        'fechaAlta' => '2026-01-01',
        'created_by' => $this->user->id,
    ]);
});

/** Payload base de una solicitud de permiso por días. */
function permisoPayload($test, array $overrides = []): array
{
    return array_merge([
        'trabajador_id' => $test->trabajador->id,
        'altaTrabajador_id' => $test->alta->id,
        'tipo' => 'J',
        'asunto' => 'Permiso por motivos de salud',
        'fechaInicio' => '2026-06-15',
        'fechaFin' => '2026-06-16',
        'documento_id' => $test->documentoId,
        'sustento' => UploadedFile::fake()->create('sustento.pdf', 100, 'application/pdf'),
    ], $overrides);
}

it('can store a permiso de dias with sustento', function () {
    post(route('permisos.store'), permisoPayload($this))->assertRedirect();

    $expediente = ConasisExpediente::where('trabajador_id', $this->trabajador->id)->latest('id')->first();

    expect($expediente)->not->toBeNull()
        ->and($expediente->estado->codigo)->toBe('PEN')
        ->and($expediente->documentos)->toHaveCount(1)
        ->and($expediente->justificaciones)->toHaveCount(1);

    Storage::assertExists($expediente->documentos->first()->rutaDoc);
});

it('can store a permiso de exoneracion de marcacion', function () {
    post(route('permisos.store'), permisoPayload($this, [
        'tipo' => 'E',
        'marcaApli' => 'E',
        'asunto' => 'Exoneración de marca de entrada',
    ]))->assertRedirect();

    $expediente = ConasisExpediente::where('trabajador_id', $this->trabajador->id)->latest('id')->first();

    expect($expediente->exoneraciones)->toHaveCount(1)
        ->and($expediente->exoneraciones->first()->marcaApli)->toBe('E');
});

it('requires sustento file and marcaApli for exoneraciones', function () {
    post(route('permisos.store'), permisoPayload($this, ['sustento' => null]))
        ->assertSessionHasErrors('sustento');

    post(route('permisos.store'), permisoPayload($this, ['tipo' => 'E', 'marcaApli' => null]))
        ->assertSessionHasErrors('marcaApli');
});

it('lists permisos por trabajador', function () {
    post(route('permisos.store'), permisoPayload($this));

    get(route('trabajadores.permisos', $this->trabajador))
        ->assertOk()
        ->assertJsonCount(1, 'data');
});

it('lists permisos por institucion', function () {
    post(route('permisos.store'), permisoPayload($this));

    get(route('instituciones.permisos', $this->ieId))
        ->assertOk()
        ->assertJsonStructure(['data', 'total', 'last_page']);
});

it('can validar a pending permiso from the IE tab', function () {
    post(route('permisos.store'), permisoPayload($this));
    $expediente = ConasisExpediente::where('trabajador_id', $this->trabajador->id)->latest('id')->first();

    post(route('permisos.validar', $expediente), ['estado' => 'VAL'])->assertRedirect();

    expect($expediente->fresh()->estado->codigo)->toBe('VAL');
});

it('requires observacion when rejecting a permiso', function () {
    post(route('permisos.store'), permisoPayload($this));
    $expediente = ConasisExpediente::where('trabajador_id', $this->trabajador->id)->latest('id')->first();

    post(route('permisos.validar', $expediente), ['estado' => 'REC'])
        ->assertSessionHasErrors('observacion');

    post(route('permisos.validar', $expediente), [
        'estado' => 'REC',
        'observacion' => 'Sustento ilegible',
    ])->assertRedirect();

    expect($expediente->fresh()->estado->codigo)->toBe('REC');
});

it('cannot validar a permiso that is not pending', function () {
    post(route('permisos.store'), permisoPayload($this));
    $expediente = ConasisExpediente::where('trabajador_id', $this->trabajador->id)->latest('id')->first();

    $expediente->update(['estado_id' => ParamEstadosTram::where('codigo', 'VAL')->value('id')]);

    post(route('permisos.validar', $expediente), ['estado' => 'VAL'])
        ->assertStatus(422);
});

it('can anular a pending permiso', function () {
    post(route('permisos.store'), permisoPayload($this));
    $expediente = ConasisExpediente::where('trabajador_id', $this->trabajador->id)->latest('id')->first();

    post(route('permisos.anular', $expediente))->assertRedirect();

    expect($expediente->fresh()->estado->codigo)->toBe('ANU');

    // Ya no aparece en el listado del trabajador
    get(route('trabajadores.permisos', $this->trabajador))
        ->assertOk()
        ->assertJsonCount(0, 'data');
});

it('can download the sustento document', function () {
    post(route('permisos.store'), permisoPayload($this));
    $expediente = ConasisExpediente::where('trabajador_id', $this->trabajador->id)->latest('id')->first();
    $documento = $expediente->documentos->first();

    get(route('documentos-tram.descargar', $documento))->assertOk();
});

it('cleans up detalle tables consistently', function () {
    post(route('permisos.store'), permisoPayload($this));

    expect(ConasisJustificaciones::where('trabajador_id', $this->trabajador->id)->count())->toBe(1)
        ->and(ConasisExoneracionesMarcacion::where('trabajador_id', $this->trabajador->id)->count())->toBe(0);
});
