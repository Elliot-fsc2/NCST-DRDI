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
            'year' => '2025-2026',
            'phase' => 'First Semester',
            'start_date' => '2025-11-01',
            'end_date' => '2026-03-19',
        ]);
    }
}
