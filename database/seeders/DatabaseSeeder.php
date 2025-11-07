<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            RoleAndAbilitySeeder::class,
            CategorySeeder::class,
            PostSeeder::class,
            OrganizationSeeder::class,
            ActivitySeeder::class,
            StatisticSeeder::class,
            FacilitySeeder::class,
            StudentSeeder::class,
            TeacherSeeder::class,
            UserSeeder::class,
            ValueSeeder::class,
            // newly added seeders
            PPDBSeeder::class,
            RegistrationSeeder::class,
            ContactSeeder::class,
            GallerySeeder::class,
        ]);
    }
}
