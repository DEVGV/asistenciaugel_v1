<?php

namespace Database\Factories;

use App\Models\CondicionesLaborales;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CondicionesLaborales>
 */
class CondicionesLaboralesFactory extends Factory
{
    protected $model = CondicionesLaborales::class;

    public function definition(): array
    {
        return [
            'nombre' => fake()->words(3, true),
            'abreviatura' => strtoupper(fake()->lexify('???')),
            'descripcion' => fake()->optional()->sentence(),
            'regimenLaboral_id' => 1,
            'tipoTrabajador_id' => 1,
            'created_by' => 1,
        ];
    }
}
