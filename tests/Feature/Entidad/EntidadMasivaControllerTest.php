<?php

use App\Models\Entidades;
use App\Models\User;

test('guests cannot access entidades-masivas endpoint', function () {
    $this->postJson(route('entidades.masivo.store'), ['filas' => []])
        ->assertUnauthorized();
});

test('authenticated users can bulk register entidades', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $ruc1 = fake()->numerify('###########');
    $ruc2 = fake()->numerify('###########');

    $filas = [
        [
            'tipoEntidad_id' => 1,
            'ruc' => $ruc1,
            'razonSocial' => 'ENTIDAD TEST UNO S.A.C.',
            'razonComercial' => 'UNO S.A.C.',
            'activo' => true,
        ],
        [
            'tipoEntidad_id' => 2,
            'ruc' => $ruc2,
            'razonSocial' => 'ENTIDAD TEST DOS S.A.C.',
            'razonComercial' => 'DOS S.A.C.',
            'activo' => true,
        ],
    ];

    $response = $this->postJson(route('entidades.masivo.store'), ['filas' => $filas]);

    $response->assertSuccessful();
    $response->assertJson(['insertados' => 2]);

    $this->assertDatabaseHas('t_entidades', ['ruc' => $ruc1]);
    $this->assertDatabaseHas('t_entidades', ['ruc' => $ruc2]);
});

test('bulk registration skips duplicate RUCs and reports errors', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $ruc = fake()->numerify('###########');

    // Pre-create entidad
    Entidades::create([
        'tipoEntidad_id' => 1,
        'ruc' => $ruc,
        'razonSocial' => 'EXISTING COMPANY S.A.C.',
        'razonComercial' => 'EXISTING',
        'created_by' => $user->id,
        'activo' => true,
    ]);

    $filas = [
        [
            'tipoEntidad_id' => 1,
            'ruc' => $ruc,
            'razonSocial' => 'DUPLICATE COMPANY S.A.C.',
            'razonComercial' => 'DUPLICATE',
            'activo' => true,
        ],
    ];

    $response = $this->postJson(route('entidades.masivo.store'), ['filas' => $filas]);

    $response->assertStatus(422);
    $response->assertJson(['insertados' => 0]);
    expect($response->json('errores_validacion'))->not->toBeEmpty();
});

test('bulk registration validates required fields per row', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $filas = [
        [
            // Missing required fields
            'ruc' => '12345678901',
            'razonSocial' => 'INCOMPLETE COMPANY',
        ],
    ];

    $response = $this->postJson(route('entidades.masivo.store'), ['filas' => $filas]);

    $response->assertStatus(422);
    $response->assertJson(['insertados' => 0]);
});

test('bulk registration requires filas array', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $this->postJson(route('entidades.masivo.store'), [])
        ->assertUnprocessable();
});
