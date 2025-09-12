<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FeeType>
 */
class FeeTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'Registration Fee',
                'Tuition Fee',
                'Transportation Fee',
                'Hostel Fee',
                'Laboratory Fee',
                'Library Fee'
            ]),
            'description' => fake()->sentence(),
            'default_amount' => fake()->randomFloat(2, 100, 5000),
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the fee type is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}