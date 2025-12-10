<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Telegramas>
 */
class TelegramasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = \App\Models\Telegramas::class;
    public function definition(): array
    {
        return [
            'id_mesa' => fake()->numberBetween(1000, 1010),
            //'id_lista' => fake()->numberBetween(1, 10),
            'provincia' => fake()->randomElement(['Buenos Aires','CABA','Catamarca','Chaco','Chubut','Córdoba','Corrientes','Entre Ríos','Formosa','Jujuy','La Pampa','La Rioja','Mendoza','Misiones','Neuquén','Río Negro','Salta','San Juan','San Luis','Santa Cruz','Santa Fe','Santiago del Estero','Tierra del Fuego','Tucumán']),
            'lista' => fake()->randomElement(['Lista A', 'Lista B', 'Lista C']),
            'votos_diputados' => fake()->numberBetween(0, 500),
            'votos_senadores' => fake()->numberBetween(0, 500),
            'blancos' => fake()->numberBetween(0, 50),
            'nulos' => fake()->numberBetween(0, 50),
            'recurridos' => fake()->numberBetween(0, 20),
        ];
    }
}
