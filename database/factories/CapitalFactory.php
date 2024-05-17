<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CapitalFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => $this->faker->numberBetween(1, 2),
            'user_group_id' => $this->faker->numberBetween(1, 2),
            'settlement_id' => NULL,
            'capital_type' => $this->faker->numberBetween(0, 1),
            'date' => $this->faker->dateTimeBetween('-1 years', 'now', 'Asia/Tokyo')->format('Y-m-d'),
            'financial_transaction_id' => $this->faker->numberBetween(1, 20),
            'money' => $this->faker->numberBetween(0, 500000),
            'share' => $this->faker->boolean(),
            'note' => $this->faker->text(10),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
