<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FacilityFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->paragraph,
            'capacity' => $this->faker->numberBetween(10, 500),
            'location' => $this->faker->address,
        ];
    }
}