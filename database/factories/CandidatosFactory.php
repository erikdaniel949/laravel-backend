<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\candidatos>
 */
class CandidatosFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = \App\Models\Candidatos::class;

    public function definition(): array
    {
        return [
            'provincia' => fake()->randomElement(['Buenos Aires','CABA','Catamarca','Chaco','Chubut','Córdoba','Corrientes','Entre Ríos','Formosa','Jujuy','La Pampa','La Rioja','Mendoza','Misiones','Neuquén','Río Negro','Salta','San Juan','San Luis','Santa Cruz','Santa Fe','Santiago del Estero','Tierra del Fuego','Tucumán']),
            'cargo' => fake()->randomElement(['DIPUTADOS', 'SENADORES']),
            'lista' => fake()->randomElement(['Lista A', 'Lista B', 'Lista C']),
            'nombre' => fake()->name(),
            'orden_en_lista' => fake()->numberBetween(1, 10),
        ];
    }
}
