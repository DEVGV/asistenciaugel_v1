<?php

use App\Models\Personas;
use App\Models\Telefonos;
use App\Models\User;

test('authenticated users can add a telefono to a persona', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $persona = Personas::create([
        'tipoDocIdentidad_id' => 1,
        'docIdentidad' => fake()->numerify('########'),
        'paterno' => 'TEL',
        'materno' => 'TEST',
        'nombre' => 'USER',
        'sexo_id' => 1,
        'pais_id' => 1,
        'created_by' => $user->id,
        'activo' => true,
    ]);

    $data = [
        'operador_id' => 1,
        'movilFijo' => 'M',
        'codigoPais' => '+51',
        'numero' => '999888777',
    ];

    $response = $this->post(route('personas.telefonos.store', $persona), $data);

    $response->assertRedirect(route('personas.show', $persona));
    $this->assertDatabaseHas('t_telefonos', [
        'persona_id' => $persona->id,
        'numero' => '999888777',
    ]);
});

test('authenticated users can delete a telefono', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $persona = Personas::create([
        'tipoDocIdentidad_id' => 1,
        'docIdentidad' => fake()->numerify('########'),
        'paterno' => 'TEL',
        'materno' => 'DEL',
        'nombre' => 'TEST',
        'sexo_id' => 1,
        'pais_id' => 1,
        'created_by' => $user->id,
        'activo' => true,
    ]);

    $telefono = Telefonos::create([
        'persona_id' => $persona->id,
        'operador_id' => 1,
        'movilFijo' => 'M',
        'numero' => '111222333',
        'created_by' => $user->id,
    ]);

    $response = $this->delete(route('telefonos.destroy', $telefono));

    $response->assertRedirect(route('personas.show', $persona));
    $this->assertDatabaseMissing('t_telefonos', ['id' => $telefono->id]);
});
