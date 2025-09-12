<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'payment_number' => 'PAY-' . fake()->unique()->numerify('######'),
            'invoice_id' => Invoice::factory(),
            'student_id' => User::factory()->state(['role' => 'student']),
            'processed_by' => User::factory()->state(['role' => 'staff']),
            'amount' => fake()->randomFloat(2, 100, 5000),
            'payment_method' => fake()->randomElement(['cash', 'bank_transfer', 'credit_card', 'online']),
            'reference_number' => fake()->optional()->regexify('[A-Z0-9]{10}'),
            'payment_date' => fake()->dateTimeBetween('-6 months', 'now'),
            'status' => fake()->randomElement(['completed', 'pending']),
            'notes' => fake()->optional()->sentence(),
        ];
    }

    /**
     * Indicate that the payment is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    /**
     * Indicate that the payment failed.
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'failed',
        ]);
    }
}