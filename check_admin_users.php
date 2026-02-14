<?php

// Script sederhana untuk mengecek apakah ada akun admin di database
require_once __DIR__.'/vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Konfigurasi dasar untuk Laravel Artisan
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Cek apakah ada pengguna admin
$adminUsers = DB::table('users')->where('is_admin', true)->get();

echo "Jumlah pengguna admin: " . count($adminUsers) . "\n";

if(count($adminUsers) > 0) {
    echo "Daftar pengguna admin:\n";
    foreach($adminUsers as $user) {
        echo "- Email: " . $user->email . ", Name: " . $user->name . "\n";
    }
} else {
    echo "Tidak ada pengguna admin ditemukan di database.\n";
    echo "Anda perlu menjalankan seeder untuk membuat akun admin:\n";
    echo "php artisan db:seed --class=AdminUserSeeder\n";
}