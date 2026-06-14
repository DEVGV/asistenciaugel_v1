<?php

namespace Database\Factories;

use App\Models\Personas;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $persona = Personas::create([
            'pais_id' => DB::table('param.t26_pais')->value('id') ?? 1,
            'tipoDocIdentidad_id' => DB::table('param.t3_tipoDocIdentidad')->value('id') ?? 1,
            'docIdentidad' => fake()->unique()->numerify('########'),
            'paterno' => fake()->lastName(),
            'materno' => fake()->lastName(),
            'nombre' => fake()->firstName(),
            'sexo_id' => DB::table('param.t00_sexos')->value('id') ?? 1,
            'activo' => true,
        ]);

        DB::table('t_emails')->insert([
            'persona_id' => $persona->id,
            'email' => fake()->unique()->safeEmail(),
            'personalInst' => 'P',
            'created_at' => now(),
        ]);

        $trabajadorId = DB::table('t_trabajador')->insertGetId([
            'persona_id' => $persona->id,
            'codigo' => 'TRAB-'.fake()->unique()->numerify('#####'),
            'activo' => true,
            'created_at' => now(),
        ]);

        return [
            'trabajador_id' => $trabajadorId,
            'login' => $persona->docIdentidad,
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
            'activo' => true,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => []);
    }

    /**
     * Indicate that the model has two-factor authentication configured.
     */
    public function withTwoFactor(): static
    {
        return $this->state(fn (array $attributes) => [
            'two_factor_secret' => encrypt('secret'),
            'two_factor_recovery_codes' => encrypt(json_encode(['recovery-code-1'])),
            'two_factor_confirmed_at' => now(),
        ]);
    }
}
