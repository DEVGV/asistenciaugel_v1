<?php

use App\Models\Entidades;
use App\Models\User;

test('guests cannot access entidades', function () {
    $this->get(route('entidades.index'))
        ->assertRedirect(route('login'));
});

test('authenticated users can visit the entidades index', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('entidades.index'));
    $response->assertSuccessful();
});

test('authenticated users can create an entidad', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $ruc = fake()->numerify('1##########');
    $data = [
        'tipoEntidad_id' => 1,
        'ruc' => $ruc,
        'razonSocial' => 'Empresa Test SAC',
        'razonComercial' => 'Test Comercial',
        'activo' => true,
    ];

    $response = $this->post(route('entidades.store'), $data);

    $response->assertRedirect(route('entidades.index'));
    $this->assertDatabaseHas('t_entidades', [
        'ruc' => $ruc,
        'razonSocial' => 'Empresa Test SAC',
    ]);
});

test('authenticated users can update an entidad', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $ruc = fake()->numerify('1##########');
    $entidad = Entidades::create([
        'tipoEntidad_id' => 1,
        'ruc' => $ruc,
        'razonSocial' => 'Old Name SAC',
        'created_by' => $user->id,
        'activo' => true,
    ]);

    $data = [
        'tipoEntidad_id' => 1,
        'ruc' => $ruc,
        'razonSocial' => 'New Name SAC',
        'activo' => true,
    ];

    $response = $this->put(route('entidades.update', $entidad), $data);

    $response->assertRedirect(route('entidades.index'));
    $this->assertDatabaseHas('t_entidades', [
        'id' => $entidad->id,
        'razonSocial' => 'New Name SAC',
    ]);
});

test('authenticated users can soft delete an entidad', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $entidad = Entidades::create([
        'tipoEntidad_id' => 1,
        'ruc' => fake()->numerify('1##########'),
        'razonSocial' => 'To Delete SAC',
        'created_by' => $user->id,
        'activo' => true,
    ]);

    $response = $this->delete(route('entidades.destroy', $entidad));

    $response->assertRedirect(route('entidades.index'));
    $this->assertDatabaseHas('t_entidades', [
        'id' => $entidad->id,
        'activo' => false,
    ]);
});
