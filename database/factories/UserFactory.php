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
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'phone' => fake()->phoneNumber(),
            'bio' => fake()->sentence(),
            'address' => fake()->address(),
            'is_admin' => false,
            'is_active' => true,
            'avatar' => null,
            'birth_date' => fake()->date(),
            'gender' => fake()->randomElement(['male', 'female']),
            'department' => null,
            'position' => null,
            'social_links' => [],
            'skills' => [],
            'last_login_at' => null,
            'two_factor_enabled' => false,
            'two_factor_secret' => null,
            'recovery_codes' => null,
            'failed_login_attempts' => 0,
            'locked_until' => null,
            'nik' => null,
            'nis' => null,
            'nik_hash' => null,
            'nis_hash' => null,
            'remember_token' => Str::random(10),
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

    /**
     * Indicate that the user is an admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_admin' => true,
        ]);
    }

    /**
     * Indicate that the user is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
