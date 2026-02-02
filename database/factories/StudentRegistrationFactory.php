<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StudentRegistration>
 */
class StudentRegistrationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'nik' => fake()->numerify('################'),
            'email' => fake()->unique()->safeEmail(),
            'birth_date' => fake()->date('Y-m-d', '-15 years'),
            'birth_place' => fake()->city(),
            'gender' => fake()->randomElement(['male', 'female']),
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'parent_name' => fake()->name(),
            'parent_phone' => fake()->phoneNumber(),
            'previous_school' => fake()->company() . ' High School',
            'desired_major' => fake()->randomElement(['IPA', 'IPS', 'Bahasa']),
            'status' => 'pending',
            'notes' => null,
            'approved_at' => null,
            'rejected_at' => null,
            'approved_by' => null,
            'rejected_by' => null,
        ];
    }
}
