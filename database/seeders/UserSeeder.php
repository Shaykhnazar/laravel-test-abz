<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Position;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create an instance of Faker
        $faker = Faker::create();

        // Fetch all position IDs
        $positionIds = Position::pluck('id')->toArray();

        // Seed 45 users
        for ($i = 0; $i < 45; $i++) {
            // Generate a unique phone number starting with +380
            $phone = '+380' . $faker->unique()->numberBetween(100000000, 999999999);

            // Generate a realistic name (2-60 characters)
            $name = $faker->firstName . ' ' . $faker->lastName;
            $name = substr($name, 0, 60);

            // Generate a unique email
            $email = $faker->unique()->safeEmail();

            // Randomly select a position ID
            $positionId = $faker->randomElement($positionIds);

            // Create the user
            User::create([
                'name'        => $name,
                'email'       => $email,
                'phone'       => $phone,
                'position_id' => $positionId,
                'photo'       => null,
                'password'    => Hash::make('secret'),
            ]);
        }
    }
}
