<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gallery;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        if (Gallery::count() > 0) {
            return;
        }

        Gallery::create([
            'path' => 'images/sample-activity.jpg',
        ]);
    }
}
