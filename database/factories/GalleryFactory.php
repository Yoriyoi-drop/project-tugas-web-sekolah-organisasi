<?php

namespace Database\Factories;

use App\Models\Gallery;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

class GalleryFactory extends Factory
{
    protected $model = Gallery::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'image_path' => $this->faker->imageUrl(640, 480),
            'type' => $this->faker->randomElement(['photo', 'video', 'document']),
            'organization_id' => Organization::factory(),
        ];
    }
}
