<?php

use App\Models\Emails;
use App\Models\Personas;
use App\Models\User;

test('authenticated users can add an email to a persona', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $persona = Personas::create([
        'tipoDocIdentidad_id' => 1,
        'docIdentidad' => fake()->numerify('########'),
        'paterno' => 'EMAIL',
        'materno' => 'TEST',
        'nombre' => 'USER',
        'sexo_id' => 1,
        'pais_id' => 1,
        'created_by' => $user->id,
        'activo' => true,
    ]);

    $data = [
        'email' => 'test@example.com',
        'personalInst' => 'P',
    ];

    $response = $this->post(route('personas.emails.store', $persona), $data);

    $response->assertRedirect(route('personas.show', $persona));
    $this->assertDatabaseHas('t_emails', [
        'persona_id' => $persona->id,
        'email' => 'test@example.com',
    ]);
});

test('authenticated users can delete an email', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $persona = Personas::create([
        'tipoDocIdentidad_id' => 1,
        'docIdentidad' => fake()->numerify('########'),
        'paterno' => 'EMAIL',
        'materno' => 'DEL',
        'nombre' => 'TEST',
        'sexo_id' => 1,
        'pais_id' => 1,
        'created_by' => $user->id,
        'activo' => true,
    ]);

    $email = Emails::create([
        'persona_id' => $persona->id,
        'email' => 'delete@example.com',
        'personalInst' => 'P',
        'created_by' => $user->id,
    ]);

    $response = $this->delete(route('emails.destroy', $email));

    $response->assertRedirect(route('personas.show', $persona));
    $this->assertDatabaseMissing('t_emails', ['id' => $email->id]);
});
