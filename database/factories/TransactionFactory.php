<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'client_id' => \App\Models\Client::factory(), // Always creates a linked client
            'transaction_date' => $this->faker->date(), // Using 'transaction_date' for clarity
            'transaction_amount' => $this->faker->randomFloat(2, 1000, 50000),
            'transaction_type_id' => \App\Models\TypeTransaction::factory(), // Always creates a linked type transaction
        ];
    }
}
