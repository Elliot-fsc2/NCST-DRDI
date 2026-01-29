<?php

namespace Database\Seeders;

use App\Models\Section;
use App\Models\Student;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a few sections
        $sections = [
            ['name' => 'Section A', 'course_id' => 1, 'semester_id' => 1, 'teacher_id' => 1],
            ['name' => 'Section B', 'course_id' => 1, 'semester_id' => 1, 'teacher_id' => 2],
            ['name' => 'Section C', 'course_id' => 2, 'semester_id' => 1, 'teacher_id' => 3],
        ];

        // Load existing students in random order and use them first
        $existing = Student::inRandomOrder()->get();

        foreach ($sections as $data) {
            $section = Section::create($data);

            // Take up to 8 students from existing pool
            $toAttach = $existing->splice(0, 8);

            // If not enough existing students, create the remaining ones
            if ($toAttach->count() < 8) {
                $needed = 8 - $toAttach->count();
                $created = Student::factory()->count($needed)->create(['course_id' => $data['course_id']]);
                $toAttach = $toAttach->concat($created);
            }

            // Attach student ids to the section
            $section->students()->attach($toAttach->pluck('id')->all());
        }
    }
}
