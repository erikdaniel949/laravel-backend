<?php

namespace Database\Seeders;

use App\Models\Mesas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MesasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Mesas::factory(10)->create();
    }
}
