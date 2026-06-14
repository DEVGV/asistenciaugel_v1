<?php

namespace Database\Factories;

use App\Models\Areas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Areas>
 */
class AreasFactory extends Factory
{
    protected $model = Areas::class;

    public function definition(): array
    {
        return [
            'nombre' => fake()->words(2, true),
            'sigla' => strtoupper(fake()->lexify('???')),
            'descripcion' => fake()->optional()->sentence(),
            'rolTrabajador_id' => null,
            'activo' => true,
        ];
    }
}
