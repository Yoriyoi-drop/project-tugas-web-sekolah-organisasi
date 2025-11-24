<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUserCommand extends Command
{
    protected $signature = 'user:create 
                            {name : Nama lengkap pengguna} 
                            {email : Alamat email pengguna} 
                            {--password= : Kata sandi pengguna} 
                            {--admin : Jadikan pengguna sebagai admin} 
                            {--phone= : Nomor telepon pengguna}
                            {--address= : Alamat pengguna}';
    
    protected $description = 'Membuat user baru dalam sistem';

    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $password = $this->option('password') ?? $this->secret('Masukkan kata sandi');
        $is_admin = $this->option('admin');
        $phone = $this->option('phone');
        $address = $this->option('address');

        if (empty($password)) {
            $this->error('Kata sandi tidak boleh kosong');
            return 1;
        }

        try {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'phone' => $phone,
                'address' => $address,
                'is_admin' => $is_admin,
                'is_active' => true,
            ]);

            $this->info("User berhasil dibuat:");
            $this->info("- ID: {$user->id}");
            $this->info("- Nama: {$user->name}");
            $this->info("- Email: {$user->email}");
            $this->info("- Admin: " . ($is_admin ? 'Ya' : 'Tidak'));

            return 0;
        } catch (\Exception $e) {
            $this->error('Gagal membuat user: ' . $e->getMessage());
            return 1;
        }
    }
}