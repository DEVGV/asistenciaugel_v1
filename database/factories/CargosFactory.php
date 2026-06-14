<?php

namespace Database\Factories;

use App\Models\Cargos;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cargos>
 */
class CargosFactory extends Factory
{
    protected $model = Cargos::class;

    public function definition(): array
    {
        return [
            'nombre' => fake()->jobTitle(),
            'abreviatura' => strtoupper(fake()->lexify('???')),
            'rolTrabajador_id' => null,
            'created_by' => 1,
            'activo' => true,
        ];
    }
}
