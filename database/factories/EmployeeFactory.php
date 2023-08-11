<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'department_id' => 1,
            'name' => fake()->name(),
            'emp_no' => fake()->unique()->randomNumber(5, true),
            'name' => fake()->name(),
            'salary' => fake()->randomNumber(5, true),
        ];
    }
}
