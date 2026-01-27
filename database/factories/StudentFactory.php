<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'course_id' => Course::inRandomOrder()->first()->id,
            'student_number' => fake()->unique()->numerify(date('Y').'-#####'),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Student $student) {
            User::create([
                'name' => $student->name,
                'email' => Str::slug($student->name).'@student.edu',
                'password' => 'password',
                'profileable_id' => $student->id,
                'profileable_type' => Student::class,
            ]);
        });
    }
}
