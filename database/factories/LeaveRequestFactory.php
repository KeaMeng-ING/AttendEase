<?php

namespace Database\Factories;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LeaveRequest>
 */
class LeaveRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-1 month', '+1 month');
        $endDate = $this->faker->dateTimeBetween($startDate, $startDate->modify('+1 week'));

        return [
            'user_id' => User::inRandomOrder()->first()->id, // Use an existing user
            'leave_type_id' => LeaveType::inRandomOrder()->first()->id, // Use an existing leave type
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'reason' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'reviewed_by' => $this->faker->boolean(50) ? User::inRandomOrder()->first()->id : null, // 50% chance of being reviewed
        ];
    }
}
