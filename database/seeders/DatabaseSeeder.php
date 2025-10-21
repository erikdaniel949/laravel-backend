<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Candidatos;
use App\Models\Listas;
use App\Models\Mesas;
use App\Models\Telegramas;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@example.com',
        ]);

        User::factory(4)->create();

        Candidatos::factory(10)->create();
        Listas::factory(10)->create();
        Mesas::factory(10)->create();
        Telegramas::factory(10)->create();
    }
}
