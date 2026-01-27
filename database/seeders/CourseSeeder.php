<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Course::create(['name' => 'BSIT', 'department_id' => 1]);
        Course::create(['name' => 'BSCS', 'department_id' => 1]);
        Course::create(['name' => 'BSA', 'department_id' => 2]);
        Course::create(['name' => 'BSBA', 'department_id' => 2]);
        Course::create(['name' => 'BSED Eng', 'department_id' => 3]);
        Course::create(['name' => 'BSED Fil', 'department_id' => 3]);
        Course::create(['name' => 'BSCrim', 'department_id' => 4]);
    }
}
