<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'nis' => (string) $this->faker->unique()->numberBetween(100000, 999999),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'class' => $this->faker->randomElement(['X IPA 1', 'X IPA 2', 'XI IPA 1', 'XI IPA 2', 'XII IPA 1', 'XII IPA 2']),
            'address' => $this->faker->address(),
        ];
    }
}
