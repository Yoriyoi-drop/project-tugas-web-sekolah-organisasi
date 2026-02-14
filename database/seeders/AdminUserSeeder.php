<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat akun admin utama
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@sekolah.org'],
            [
                'name' => 'Administrator Sekolah',
                'password' => Hash::make('Admin123!'),
                'phone' => '+6281234567890',
                'bio' => 'Administrator sistem manajemen sekolah',
                'address' => 'Jl. Pendidikan No. 1, Jakarta Selatan',
                'is_admin' => true,
                'is_active' => true,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Membuat akun admin cadangan
        $backupAdmin = User::firstOrCreate(
            ['email' => 'admin2@sekolah.org'],
            [
                'name' => 'Admin Cadangan',
                'password' => Hash::make('Admin123!'),
                'phone' => '+6281234567891',
                'bio' => 'Administrator cadangan sistem',
                'address' => 'Jl. Pendidikan No. 2, Jakarta Selatan',
                'is_admin' => true,
                'is_active' => true,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Jika ingin menampilkan pesan bahwa akun telah dibuat
        if ($adminUser->wasRecentlyCreated) {
            $this->command->info('Akun admin utama berhasil dibuat:');
            $this->command->info('- Email: admin@sekolah.org');
            $this->command->info('- Password: Admin123!');
        } else {
            $this->command->info('Akun admin utama sudah ada');
        }

        if ($backupAdmin->wasRecentlyCreated) {
            $this->command->info('Akun admin cadangan berhasil dibuat:');
            $this->command->info('- Email: admin2@sekolah.org');
            $this->command->info('- Password: Admin123!');
        } else {
            $this->command->info('Akun admin cadangan sudah ada');
        }
    }
}