<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'department_id' => Department::inRandomOrder()->first()->id,
            'role' => 'teacher',
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Teacher $teacher) {
            User::create([
                'name' => $teacher->name,
                'email' => Str::slug($teacher->name).'@teacher.edu',
                'password' => 'password',
                'profileable_id' => $teacher->id,
                'profileable_type' => Teacher::class,
            ]);
        });
    }
}
