<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Ability;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RoleAndAbilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create abilities
        $abilities = [
            ['name' => 'Manage Members', 'slug' => 'manage_members'],
            ['name' => 'View Reports', 'slug' => 'view_reports'],
            ['name' => 'Edit Records', 'slug' => 'edit_records'],
            ['name' => 'Manage Finances', 'slug' => 'manage_finances'],
            ['name' => 'View Only', 'slug' => 'view_only'],
            ['name' => 'Manage Security', 'slug' => 'manage_security'],
            ['name' => 'View Audit', 'slug' => 'view_audit'],
        ];

        foreach ($abilities as $ability) {
            Ability::create([
                'name' => $ability['name'],
                'slug' => $ability['slug'],
                'description' => 'Allows user to ' . Str::lower($ability['name']),
            ]);
        }

        // Create roles with their abilities
        $roles = [
            [
                'name' => 'Ketua',
                'slug' => 'ketua',
                'abilities' => ['manage_members', 'view_reports', 'manage_security', 'view_audit'],
            ],
            [
                'name' => 'Sekretaris',
                'slug' => 'sekretaris',
                'abilities' => ['edit_records', 'view_reports'],
            ],
            [
                'name' => 'Bendahara',
                'slug' => 'bendahara',
                'abilities' => ['manage_finances', 'view_reports'],
            ],
            [
                'name' => 'Anggota',
                'slug' => 'anggota',
                'abilities' => ['view_only'],
            ],
        ];

        foreach ($roles as $roleData) {
            $role = Role::create([
                'name' => $roleData['name'],
                'slug' => $roleData['slug'],
                'description' => $roleData['name'] . ' role in the organization',
            ]);

            $abilities = Ability::whereIn('slug', $roleData['abilities'])->get();
            $role->abilities()->attach($abilities);
        }
    }
}
