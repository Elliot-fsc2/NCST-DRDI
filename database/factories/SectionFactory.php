<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Semester;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

class SectionFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'course_id' => Course::factory(),
            'semester_id' => Semester::factory(),
            'teacher_id' => Teacher::factory(),
        ];
    }
}
