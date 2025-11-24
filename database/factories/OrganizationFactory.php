<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrganizationFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'type' => $this->faker->word,
            'description' => $this->faker->paragraph,
            'icon' => $this->faker->word,
            'is_active' => true,
            'order' => $this->faker->numberBetween(1, 10),
        ];
    }
}