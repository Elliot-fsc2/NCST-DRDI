<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SemesterFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'year' => fake()->word(),
            'phase' => fake()->word(),
            'start_date' => fake()->date(),
            'end_date' => fake()->date(),
        ];
    }
}
