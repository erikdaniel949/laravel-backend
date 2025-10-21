<?php

namespace Database\Seeders;

use App\Models\Telegramas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TelegramasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Telegramas::factory(10)->create();
    }
}
