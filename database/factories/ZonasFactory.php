<?php

namespace Database\Factories;

use App\Models\Zonas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Zonas>
 */
class ZonasFactory extends Factory
{
    protected $model = Zonas::class;

    public function definition(): array
    {
        return [
            'tipoZona_id' => 1,
            'distrito_id' => null,
            'nombre' => fake()->words(2, true),
            'abreviatura' => strtoupper(fake()->lexify('???')),
            'created_by' => null,
            'activo' => true,
        ];
    }
}
