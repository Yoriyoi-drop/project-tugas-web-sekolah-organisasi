<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Create default categories for the application
        $categories = [
            [
                'name' => 'Berita',
                'slug' => 'berita',
                'description' => 'Berita terbaru dari sekolah',
                'is_active' => true
            ],
            [
                'name' => 'Pengumuman',
                'slug' => 'pengumuman',
                'description' => 'Pengumuman penting dari sekolah',
                'is_active' => true
            ],
            [
                'name' => 'Kegiatan',
                'slug' => 'kegiatan',
                'description' => 'Kegiatan sekolah dan organisasi',
                'is_active' => true
            ],
            [
                'name' => 'Artikel',
                'slug' => 'artikel',
                'description' => 'Artikel edukatif dan informatif',
                'is_active' => true
            ],
            [
                'name' => 'Edukasi',
                'slug' => 'edukasi',
                'description' => 'Artikel dan informasi edukasi',
                'is_active' => true
            ],
            [
                'name' => 'Teknologi',
                'slug' => 'teknologi',
                'description' => 'Perkembangan teknologi pendidikan',
                'is_active' => true
            ],
            [
                'name' => 'Prestasi',
                'slug' => 'prestasi',
                'description' => 'Prestasi siswa dan sekolah',
                'is_active' => true
            ],
            [
                'name' => 'Ekstrakurikuler',
                'slug' => 'ekstrakurikuler',
                'description' => 'Informasi tentang kegiatan ekstrakurikuler',
                'is_active' => true
            ]
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug']], // Find by slug
                $category // Create with full data if not found
            );
        }

        // Create additional categories from environment if specified
        if (env('ADD_SAMPLE_CATEGORIES', false)) {
            $sampleCategories = [
                ['name' => 'Olahraga', 'slug' => 'olahraga', 'description' => 'Berita olahraga sekolah', 'is_active' => true],
                ['name' => 'Seni', 'slug' => 'seni', 'description' => 'Kegiatan seni dan budaya', 'is_active' => true],
                ['name' => 'Sains', 'slug' => 'sains', 'description' => 'Kegiatan dan informasi sains', 'is_active' => true],
            ];

            foreach ($sampleCategories as $category) {
                Category::firstOrCreate(
                    ['slug' => $category['slug']],
                    $category
                );
            }
        }
    }
}
