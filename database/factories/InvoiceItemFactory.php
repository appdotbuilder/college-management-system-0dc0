<?php

namespace Database\Factories;

use App\Models\FeeType;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InvoiceItem>
 */
class InvoiceItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $amount = fake()->randomFloat(2, 100, 2000);
        $quantity = fake()->numberBetween(1, 3);
        $total = $amount * $quantity;

        return [
            'invoice_id' => Invoice::factory(),
            'fee_type_id' => FeeType::factory(),
            'description' => fake()->sentence(),
            'amount' => $amount,
            'quantity' => $quantity,
            'total' => $total,
        ];
    }
}