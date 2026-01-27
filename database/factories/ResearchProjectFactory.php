<?php

namespace Database\Factories;

use App\Models\ResearchGroup;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResearchProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'description' => fake()->text(),
            'research_group_id' => ResearchGroup::factory(),
            'assigned_teacher_id' => Teacher::factory(),
        ];
    }
}
