<?php

namespace Database\Factories;

use App\Models\OrganizationActivity;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrganizationActivity>
 */
class OrganizationActivityFactory extends Factory
{
    protected $model = OrganizationActivity::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organization_id' => Organization::factory(),
            'created_by' => User::factory(),
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'type' => $this->faker->randomElement(['event', 'meeting', 'workshop', 'competition', 'other']),
            'status' => $this->faker->randomElement(['planning', 'upcoming', 'ongoing', 'completed', 'cancelled']),
            'start_datetime' => $this->faker->dateTimeBetween('+1 week', '+1 month'),
            'end_datetime' => $this->faker->dateTimeBetween('+1 month', '+2 months'),
            'location' => $this->faker->address,
            'is_online' => $this->faker->boolean,
            'max_participants' => $this->faker->numberBetween(10, 100),
            'registered_count' => $this->faker->numberBetween(0, 50),
            'registration_required' => $this->faker->boolean(80),
            'registration_deadline' => $this->faker->dateTimeBetween('now', '+1 week'),
            'view_count' => $this->faker->numberBetween(0, 100),
            'is_featured' => $this->faker->boolean(20),
        ];
    }
}
