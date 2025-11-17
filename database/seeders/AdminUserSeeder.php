<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => env('ADMIN_DEFAULT_EMAIL', 'admin@manu.com')],
            [
                'name' => env('ADMIN_DEFAULT_NAME', 'Administrator'),
                'password' => Hash::make(env('ADMIN_DEFAULT_PASSWORD', 'admin123')),
                'is_admin' => true,
            ]
        );


    }
}
