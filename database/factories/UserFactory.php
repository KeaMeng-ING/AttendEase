<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'employee_id' => fake()->unique()->numberBetween(1000, 9999),
            'department' => fake()->randomElement([
                'IT',
                'Sales',
                'Marketing',
                'HR',
                'Finance',
                'Operations',
                'Customer Service',
                'Engineering',
                'Legal',
                'Accounting'
            ]),
            'email' => fake()->unique()->safeEmail(),
            'phone_number' => fake()->optional(0.8)->numerify('+1##########'), // 80% chance, US format
            'password' => Hash::make('password'), // Default password for all users
            'role' => fake()->randomElement(['employee', 'manager', 'admin']),
            'status' => fake()->randomElement(['active', 'inactive']),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
