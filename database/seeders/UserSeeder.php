<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat user admin
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('admin123'),
                'phone' => '081234567890',
                'bio' => 'Administrator sistem',
                'address' => 'Jl. Admin No. 1, Jakarta',
                'is_admin' => true,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Membuat user biasa
        User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('password123'),
                'phone' => '081234567891',
                'bio' => 'Seorang siswa aktif',
                'address' => 'Jl. Sekolah No. 2, Bandung',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Membuat beberapa user sample
        $users = [
            [
                'name' => 'Guru Bahasa Indonesia',
                'email' => 'guru.bahasa@example.com',
                'password' => Hash::make('guru123'),
                'phone' => '081234567892',
                'bio' => 'Guru Bahasa Indonesia',
                'address' => 'Jl. Pendidikan No. 3, Surabaya',
                'department' => 'Bahasa',
                'position' => 'Guru',
                'is_active' => true,
            ],
            [
                'name' => 'Siswa Kelas X',
                'email' => 'siswa.kelasx@example.com',
                'password' => Hash::make('siswa123'),
                'phone' => '081234567893',
                'bio' => 'Siswa kelas X',
                'address' => 'Jl. Pelajar No. 4, Yogyakarta',
                'nis' => '2023123456',
                'is_active' => true,
            ],
            [
                'name' => 'Wali Murid',
                'email' => 'walimurid@example.com',
                'password' => Hash::make('orangtua123'),
                'phone' => '081234567894',
                'bio' => 'Wali murid siswa',
                'address' => 'Jl. Orangtua No. 5, Semarang',
                'is_active' => true,
            ]
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                array_merge($userData, ['email_verified_at' => now()])
            );
        }
    }
}