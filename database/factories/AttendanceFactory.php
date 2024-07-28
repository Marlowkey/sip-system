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
        return [
            'user_id' => User::factory(),
            'date' => $this->faker->dateTimeThisYear('+6 months'),
            'time_in_am' => $this->faker->time(),
            'time_out_am' => $this->faker->time(),
            'time_in_pm' => $this->faker->time(),
            'time_out_pm' => $this->faker->time(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
