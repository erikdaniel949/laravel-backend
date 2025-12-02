<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mesas>
 */
class MesasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = \App\Models\Mesas::class;

    public function definition(): array
    {
        return [
            'id_mesa' => fake()->unique()->numberBetween(1000, 1010),
            'provincia' => fake()->randomElement(['Buenos Aires','CABA','Catamarca','Chaco','Chubut','Córdoba','Corrientes','Entre Ríos','Formosa','Jujuy','La Pampa','La Rioja','Mendoza','Misiones','Neuquén','Río Negro','Salta','San Juan','San Luis','Santa Cruz','Santa Fe','Santiago del Estero','Tierra del Fuego','Tucumán']),
            'circuito' => fake()->numberBetween(100, 1000),
            'establecimiento' => fake()->randomElement(['Escuela 1', 'Escuela 2', 'Escuela 3']),
            'electores' => fake()->numberBetween(300, 500),
        ];
    }
}
