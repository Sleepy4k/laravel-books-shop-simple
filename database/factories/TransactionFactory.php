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
            'user_id' => rand(1,10),
            'book_id' => rand(1,10),
            'quantity' => rand(1,5),
            'total_payment' => rand(50,250) . '000',
            'payment_date' => fake()->dateTimeBetween('2022-11-01', now()->format('Y-m-d'))
        ];
    }
}
