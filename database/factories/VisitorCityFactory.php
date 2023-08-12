<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VisitorCity>
 */
class VisitorCityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'short_url_id' => 1,
            'city' => fake()->country(),
            'visit_at' => fake()->date(),
            'total_count' => fake()->randomNumber(5, true),
        ];
    }
}
