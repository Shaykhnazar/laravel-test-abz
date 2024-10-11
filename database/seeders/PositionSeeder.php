<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            ['id' => 1, 'name' => 'Lawyer'],
            ['id' => 2, 'name' => 'Content manager'],
            ['id' => 3, 'name' => 'Security'],
            ['id' => 4, 'name' => 'Designer'],
        ];

        foreach ($positions as $position) {
            // Use updateOrCreate to avoid duplicates
            Position::updateOrCreate(['id' => $position['id']], $position);
        }
    }
}
