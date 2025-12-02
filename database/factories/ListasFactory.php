<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Listas>
 */
class ListasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = \App\Models\Listas::class;

    public function definition(): array
    {
        return [
            'provincia' => fake()->randomElement(['Buenos Aires','CABA','Catamarca','Chaco','Chubut','Córdoba','Corrientes','Entre Ríos','Formosa','Jujuy','La Pampa','La Rioja','Mendoza','Misiones','Neuquén','Río Negro','Salta','San Juan','San Luis','Santa Cruz','Santa Fe','Santiago del Estero','Tierra del Fuego','Tucumán']),
            'cargo' => fake()->randomElement(['DIPUTADOS', 'SENADORES']),
            'lista' => fake()->randomElement(['Lista A', 'Lista B', 'Lista C']),
            'alianza' => fake()->randomElement(['Frente 1', 'Frente 2', 'Frente 3']),
        ];
    }
}
