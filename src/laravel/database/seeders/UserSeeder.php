<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        for($i=1; $i<=20; $i++){
            $user = new User();
            $user->name = $faker->name;
            $user->email = $faker->unique()->safeEmail;
            $user->password = Hash::make('password');
            $user->save();
        }
    }
}
