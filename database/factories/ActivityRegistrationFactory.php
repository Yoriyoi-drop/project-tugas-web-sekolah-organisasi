<?php

namespace Database\Factories;

use App\Models\ActivityRegistration;
use App\Models\OrganizationActivity;
use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityRegistrationFactory extends Factory
{
    protected $model = ActivityRegistration::class;

    public function definition()
    {
        return [
            'activity_id' => OrganizationActivity::factory(),
            'member_id' => Member::factory(),
            'registered_by' => User::factory(),
            'status' => $this->faker->randomElement(['registered', 'confirmed', 'attended', 'absent', 'cancelled']),
            'notes' => $this->faker->sentence,
            'responses' => $this->faker->randomElements([
                'dietary' => $this->faker->randomElement(['vegetarian', 'halal', 'none']),
                'tshirt_size' => $this->faker->randomElement(['S', 'M', 'L', 'XL']),
                'special_needs' => $this->faker->boolean ? $this->faker->sentence : null
            ], $this->faker->numberBetween(0, 2)),
            'checked_in_at' => $this->faker->optional(0.7)->dateTimeBetween('-1 week', 'now'),
            'checked_out_at' => $this->faker->optional(0.5)->dateTimeBetween('-1 week', 'now'),
            'feedback' => $this->faker->optional(0.6)->sentence,
            'rating' => $this->faker->optional(0.6)->numberBetween(1, 5)
        ];
    }
}
