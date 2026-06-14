<?php

use App\Models\Domicilios;
use App\Models\Personas;
use App\Models\User;
use App\Models\Zonas;

beforeEach(function () {
    $this->zona = Zonas::firstOrCreate(
        ['nombre' => 'Zona Test'],
        ['tipoZona_id' => 1, 'distrito_id' => 1, 'created_by' => 1, 'activo' => true]
    );
});

test('authenticated users can add a domicilio to a persona', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $persona = Personas::create([
        'tipoDocIdentidad_id' => 1,
        'docIdentidad' => fake()->numerify('########'),
        'paterno' => 'DOM',
        'materno' => 'TEST',
        'nombre' => 'USER',
        'sexo_id' => 1,
        'pais_id' => 1,
        'created_by' => $user->id,
        'activo' => true,
    ]);

    $data = [
        'domicilio' => 'Av. Perú 123, Lima',
        'zona_id' => $this->zona->id,
    ];

    $response = $this->post(route('personas.domicilios.store', $persona), $data);

    $response->assertRedirect(route('personas.show', $persona));
    $this->assertDatabaseHas('t_domicilios', [
        'persona_id' => $persona->id,
        'domicilio' => 'Av. Perú 123, Lima',
    ]);
});

test('authenticated users can delete a domicilio', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $persona = Personas::create([
        'tipoDocIdentidad_id' => 1,
        'docIdentidad' => fake()->numerify('########'),
        'paterno' => 'DOM',
        'materno' => 'DEL',
        'nombre' => 'TEST',
        'sexo_id' => 1,
        'pais_id' => 1,
        'created_by' => $user->id,
        'activo' => true,
    ]);

    $domicilio = Domicilios::create([
        'persona_id' => $persona->id,
        'domicilio' => 'Jr. Cusco 456',
        'zona_id' => $this->zona->id,
        'created_by' => $user->id,
    ]);

    $response = $this->delete(route('domicilios.destroy', $domicilio));

    $response->assertRedirect(route('personas.show', $persona));
    $this->assertDatabaseMissing('t_domicilios', ['id' => $domicilio->id]);
});
