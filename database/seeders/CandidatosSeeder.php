<?php

namespace Database\Seeders;

use App\Models\Candidatos;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CandidatosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Candidatos::factory(10)->create();
    }
}
