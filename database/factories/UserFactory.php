<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password = null;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'middle_initial' => $this->faker->optional()->lexify('?').'.', // 'A.
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'course' =>  $this->faker->randomElement(['Computer Science', 'Information Technology', 'Information System']),
            'block' => $this->faker->randomElement(['A', 'B', 'C']),
            'password' => static::$password ??= Hash::make('password'),
            'role' => $this->faker->randomElement(['student', 'coordinator', 'admin']),
            'host_training_establishment' => $this->faker->optional()->company(),
            'remember_token' => Str::random(10),
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
