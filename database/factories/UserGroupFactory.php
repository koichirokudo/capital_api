<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserGroupFactory extends Factory
{
    public function definition(): array
    {
        return [
            'group_name' => $this->faker->name(),
            'invite_code' => $this->faker->realText(10),
            'invite_limit' => $this->faker->dateTimeBetween('now', '+1 years', 'Asia/Tokyo')->format('Y-m-d'),
            'start_day' => $this->faker->numberBetween(1, 2),
        ];
    }
}
