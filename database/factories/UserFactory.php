<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker_en = \Faker\Factory::create('en_US');
        return [
            'user_group_id' => $this->faker->numberBetween(1, 2),
            'auth_type' => $this->faker->numberBetween(0, 1),
            'profile_image' => $this->faker->imageUrl(),
            'name' => $faker_en->unique()->firstName . '_' . $faker_en->unique()->lastName,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'token' => $this->faker->sha256(),
            'delete' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
