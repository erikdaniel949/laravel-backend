<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Telegramas;

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

    protected $model = Telegramas::class;
    public function definition(): array
    {
        return [
            'id_lista' => fake()->numberBetween(1, 100),
            'id_mesa' => fake()->numberBetween(1000, 1010),
            'provincia' => fake()->state(),
            'lista' => fake()->randomElement(['Lista A', 'Lista B', 'Lista C']),
            'votos_diputados' => fake()->numberBetween(0, 500),
            'votos_senadores' => fake()->numberBetween(0, 500),
            'blancos' => fake()->numberBetween(0, 50),
            'nulos' => fake()->numberBetween(0, 50),
            'recurridos' => fake()->numberBetween(0, 20),
        ];
    }
}
