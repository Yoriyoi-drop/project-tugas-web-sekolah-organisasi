<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Organization;

class RegistrationFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'class' => $this->faker->word,
            'nis' => $this->faker->numerify('##########'),
            'address' => $this->faker->address,
            'motivation' => $this->faker->paragraph,
            'organization_id' => Organization::factory(),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
        ];
    }
}