<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $issueDate = fake()->dateTimeBetween('-6 months', 'now');
        $dueDate = fake()->dateTimeBetween($issueDate, '+3 months');
        $totalAmount = fake()->randomFloat(2, 500, 10000);
        $paidAmount = fake()->boolean(60) ? fake()->randomFloat(2, 0, $totalAmount) : 0;

        return [
            'invoice_number' => 'INV-' . fake()->unique()->numerify('######'),
            'student_id' => User::factory()->state(['role' => 'student']),
            'created_by' => User::factory()->state(['role' => 'staff']),
            'issue_date' => $issueDate,
            'due_date' => $dueDate,
            'total_amount' => $totalAmount,
            'paid_amount' => $paidAmount,
            'status' => $this->getStatus($totalAmount, $paidAmount, $dueDate),
            'notes' => fake()->optional()->sentence(),
        ];
    }

    /**
     * Get status based on payment amount and due date.
     */
    protected function getStatus(float $totalAmount, float $paidAmount, $dueDate): string
    {
        if ($paidAmount >= $totalAmount) {
            return 'paid';
        } elseif ($paidAmount > 0) {
            return 'partial';
        } elseif (now()->isAfter($dueDate)) {
            return 'overdue';
        }
        
        return 'pending';
    }

    /**
     * Indicate that the invoice is paid.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'paid_amount' => $attributes['total_amount'],
            'status' => 'paid',
        ]);
    }

    /**
     * Indicate that the invoice is overdue.
     */
    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'due_date' => fake()->dateTimeBetween('-3 months', '-1 day'),
            'paid_amount' => 0,
            'status' => 'overdue',
        ]);
    }
}