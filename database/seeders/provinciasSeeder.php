<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Provincias;

class provinciasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provincias = [
            ['provincia' => 'Buenos Aires'],
            ['provincia' => 'Catamarca'],
            ['provincia' => 'Chaco'],
            ['provincia' => 'Chubut'],
            ['provincia' => 'Córdoba'],
            ['provincia' => 'Corrientes'],
            ['provincia' => 'Entre Ríos'],
            ['provincia' => 'Formosa'],
            ['provincia' => 'Jujuy'],
            ['provincia' => 'La Pampa'],
            ['provincia' => 'La Rioja'],
            ['provincia' => 'Mendoza'],
            ['provincia' => 'Misiones'],
            ['provincia' => 'Neuquén'],
            ['provincia' => 'Río Negro'],
            ['provincia' => 'Salta'],
            ['provincia' => 'San Juan'],
            ['provincia' => 'San Luis'],
            ['provincia' => 'Santa Cruz'],
            ['provincia' => 'Santa Fe'],
            ['provincia' => 'Santiago del Estero'],
            ['provincia' => 'Tierra del Fuego'],
            ['provincia' => 'Tucumán'],
            ['provincia' => 'CABA'],
        ];

        foreach ($provincias as $provincia) {
            Provincias::create($provincia);  // Usar create para insertar con timestamps
        }
    }
}
