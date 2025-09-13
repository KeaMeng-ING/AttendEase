<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $clockIn = $this->faker->dateTimeBetween('-1 week', 'now');
        $clockOut = $this->faker->boolean(80) ? $this->faker->dateTimeBetween($clockIn, 'now') : null; // 80% chance of having a clock-out time

        return [
            // 'user_id' => User::factory(), // Create a new user or use an existing one
            'user_id' => User::inRandomOrder()->first()->id, // Use an existing user
            'clock_in' => $clockIn,
            'clock_out' => $clockOut,
            'status' => $this->faker->randomElement(['present', 'absent', 'late', 'on_leave']),
            'attendance_date' => $this->faker->date(),
        ];
    }
}
