<?php

namespace Database\Seeders;

use App\Models\Semester;
use Illuminate\Database\Seeder;

class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Semester::create([
            'name' => '2024-2025 First Semester',
            'start_date' => '2024-08-01',
            'end_date' => '2024-12-19',
        ]);
    }
}
