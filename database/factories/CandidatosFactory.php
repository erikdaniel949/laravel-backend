<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Candidatos>
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
            'provincia' => $this->faker->randomElement([
                'Buenos Aires',
                'Catamarca',
                'Chaco',
                'Chubut',
                'Córdoba',
                'Corrientes',
                'Entre Ríos',
                'Formosa',
                'Jujuy',
                'La Pampa',
                'La Rioja',
                'Mendoza',
                'Misiones',
                'Neuquén',
                'Río Negro',
                'Salta',
                'San Juan',
                'San Luis',
                'Santa Cruz',
                'Santa Fe',
                'Santiago del Estero',
                'Tierra del Fuego',
                'Tucumán',
                'CABA'
            ]),
            'cargo' => $this->faker->randomElement(['DIPUTADOS', 'SENADORES']),
            'lista' => $this->faker->randomElement(['Lista A', 'Lista B', 'Lista C']),
            'nombre' => $this->faker->name(),
            'orden_en_lista' => $this->faker->numberBetween(1, 10),
        ];
    }
}
