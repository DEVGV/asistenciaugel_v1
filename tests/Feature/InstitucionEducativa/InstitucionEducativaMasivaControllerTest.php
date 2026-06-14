<?php

use App\Models\InstitucionesEduc;
use App\Models\Param\ParamModalidadesForm;
use App\Models\Param\ParamNivelesCiclo;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAs($this->user);
    $this->modalidadId = ParamModalidadesForm::first()?->id ?? 1;
    $this->nivelId = ParamNivelesCiclo::first()?->id ?? 1;
});

it('can store instituciones educativas in bulk', function () {
    $payload = [
        'filas' => [
            [
                'codigoInstitucion' => 'MASV'.fake()->unique()->numerify('####'),
                'nombreLegal' => 'IE Masiva Test 1',
                'modalidadFormativa_id' => $this->modalidadId,
                'nivelCiclo_id' => $this->nivelId,
            ],
            [
                'codigoInstitucion' => 'MASV'.fake()->unique()->numerify('####'),
                'nombreLegal' => 'IE Masiva Test 2',
                'modalidadFormativa_id' => $this->modalidadId,
                'nivelCiclo_id' => $this->nivelId,
            ],
        ],
    ];

    $response = postJson(route('instituciones.masivo.store'), $payload)
        ->assertOk()
        ->assertJsonStructure(['message', 'insertados', 'errores_validacion', 'errores_db']);

    expect($response->json('insertados'))->toBe(2);
});

it('rejects duplicate codigoInstitucion within same batch', function () {
    $codigo = 'DUP'.fake()->unique()->numerify('####');

    $response = postJson(route('instituciones.masivo.store'), [
        'filas' => [
            [
                'codigoInstitucion' => $codigo,
                'nombreLegal' => 'IE Duplicada 1',
                'modalidadFormativa_id' => $this->modalidadId,
                'nivelCiclo_id' => $this->nivelId,
            ],
            [
                'codigoInstitucion' => $codigo,
                'nombreLegal' => 'IE Duplicada 2',
                'modalidadFormativa_id' => $this->modalidadId,
                'nivelCiclo_id' => $this->nivelId,
            ],
        ],
    ]);

    // First row inserted, second rejected as duplicate in batch
    expect($response->json('insertados'))->toBe(1)
        ->and($response->json('errores_validacion'))->not->toBeEmpty();
});

it('rejects codigoInstitucion already in the database', function () {
    $codigo = 'EXIST'.fake()->unique()->numerify('###');

    InstitucionesEduc::create([
        'codigoInstitucion' => $codigo,
        'nombreLegal' => 'IE ya existente',
        'modalidadFormativa_id' => $this->modalidadId,
        'nivelCiclo_id' => $this->nivelId,
        'created_by' => $this->user->id,
    ]);

    $response = postJson(route('instituciones.masivo.store'), [
        'filas' => [
            [
                'codigoInstitucion' => $codigo,
                'nombreLegal' => 'IE Repetida',
                'modalidadFormativa_id' => $this->modalidadId,
                'nivelCiclo_id' => $this->nivelId,
            ],
        ],
    ])->assertStatus(422);

    expect($response->json('insertados'))->toBe(0)
        ->and($response->json('errores_validacion'))->not->toBeEmpty();
});

it('validates required fields per row', function () {
    $response = postJson(route('instituciones.masivo.store'), [
        'filas' => [
            [
                'codigoInstitucion' => 'VALID'.fake()->unique()->numerify('###'),
                'nombreLegal' => 'IE Válida',
                'modalidadFormativa_id' => $this->modalidadId,
                'nivelCiclo_id' => $this->nivelId,
            ],
            [
                // Missing nombreLegal, modalidadFormativa_id, nivelCiclo_id
                'codigoInstitucion' => 'INVALIDO001',
            ],
        ],
    ])->assertOk();

    expect($response->json('insertados'))->toBe(1)
        ->and($response->json('errores_validacion'))->not->toBeEmpty();
});

it('returns 422 when no valid rows exist', function () {
    postJson(route('instituciones.masivo.store'), [
        'filas' => [
            [
                // Missing all required fields
                'codigoInstitucion' => 'BAD',
            ],
        ],
    ])->assertStatus(422)->assertJson(['insertados' => 0]);
});

it('requires at least one fila', function () {
    postJson(route('instituciones.masivo.store'), [])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['filas']);
});

it('stores optional fields when provided', function () {
    $codigo = 'OPT'.fake()->unique()->numerify('####');

    $response = postJson(route('instituciones.masivo.store'), [
        'filas' => [
            [
                'codigoInstitucion' => $codigo,
                'codigoModular' => '9999999',
                'nombreLegal' => 'IE Con Opcionales',
                'modalidadFormativa_id' => $this->modalidadId,
                'nivelCiclo_id' => $this->nivelId,
                'fechaInicio' => '2024-01-01',
            ],
        ],
    ])->assertOk();

    expect($response->json('insertados'))->toBe(1);

    $ie = InstitucionesEduc::where('codigoInstitucion', $codigo)->first();
    expect($ie)->not->toBeNull()
        ->and($ie->codigoModular)->toBe('9999999')
        ->and($ie->fechaInicio)->toBe('2024-01-01');
});
