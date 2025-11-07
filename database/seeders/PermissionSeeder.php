<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This seeder is safe to include even if spatie/laravel-permission
     * is not installed yet. It will print an informative message and
     * exit early when the package's classes are not available.
     */
    public function run()
    {
        if (!class_exists(\Spatie\Permission\Models\Permission::class) || !class_exists(\Spatie\Permission\Models\Role::class)) {
            $this->command->info('Spatie Permission package not installed. Run: composer require spatie/laravel-permission and then publish/migrate before re-running this seeder.');
            return;
        }

        $permissions = [
            'manage_users',
            'manage_posts',
            'view_reports',
            'manage_security',
        ];

        foreach ($permissions as $perm) {
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $perm]);
        }

        // Create role respecting any existing schema (some installations have a `slug` column)
        if (Schema::hasColumn('roles', 'slug')) {
            $slug = Str::slug('admin');
            \Spatie\Permission\Models\Role::firstOrCreate([
                'slug' => $slug,
            ], [
                'name' => 'admin',
                'guard_name' => config('auth.defaults.guard', 'web'),
            ]);
            // sync permissions via role model instance
            $role = \Spatie\Permission\Models\Role::where('slug', $slug)->first();
            if ($role) {
                $role->syncPermissions($permissions);
            }
        } else {
            $adminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
            $adminRole->syncPermissions($permissions);
        }

        // Optionally assign admin role to the first user (if users table exists)
        if (\App\Models\User::count() > 0) {
            $first = \App\Models\User::first();
            if ($first) {
                $first->assignRole('admin');
                $this->command->info('Assigned admin role to user id=' . $first->id);
            }
        }

        $this->command->info('PermissionSeeder completed.');
    }
}
