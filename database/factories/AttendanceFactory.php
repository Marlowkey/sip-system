<?php

namespace Database\Factories;

use Carbon\Carbon;
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
        return [
            'user_id' => User::factory(),
            'date' => $this->faker->dateTimeThisYear('+7 months'),
            'time_in_am' => $this->faker->time(),
            'time_out_am' => $this->faker->time(),
            'time_in_pm' => $this->faker->time(),
            'time_out_pm' => $this->faker->time(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function forUserOnDate($userId, $date): static
    {
        return $this->state(function () use ($userId, $date) {
            return [
                'user_id' => $userId,
                'date' => $date,
                'created_at' => $date,
                'updated_at' => $date,
            ];
        });
    }
}
