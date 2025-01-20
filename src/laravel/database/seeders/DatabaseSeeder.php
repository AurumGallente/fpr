<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Database\Seeders\UserSeeder;
use Database\Seeders\ProjectSeeder;
use Database\Seeders\TextSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ProjectSeeder::class,
            TextSeeder::class,
        ]);
    }
}
