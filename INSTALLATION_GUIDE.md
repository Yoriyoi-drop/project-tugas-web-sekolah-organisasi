# Panduan Instalasi & Konfigurasi Sistem

## 1. Prasyarat Sistem

### 1.1 Server Requirements
- **PHP**: Versi 8.2 atau lebih baru
- **Database**: SQLite (default), MySQL 5.7+/8.0, PostgreSQL 10+
- **Web Server**: Apache/Nginx
- **Node.js**: 16.x atau lebih baru (untuk asset compilation)

### 1.2 PHP Extensions
- BCMath PHP Extension
- Ctype PHP Extension
- cURL PHP Extension
- DOM PHP Extension
- Fileinfo PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension

## 2. Instalasi Langkah demi Langkah

### 2.1 Clone Repository
```bash
git clone https://github.com/Yoriyoi-drop/organisasi-sekolah-web-2.0.git
cd organisasi-sekolah-web-2.0
```

### 2.2 Instalasi Dependencies
```bash
# Instalasi PHP dependencies
composer install

# Instalasi Node.js dependencies (opsional)
npm install
```

### 2.3 Konfigurasi Environment
```bash
# Copy file environment
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 2.4 Konfigurasi Database
```bash
# Untuk SQLite (default)
touch database/database.sqlite

# Konfigurasi di .env file
DB_CONNECTION=sqlite
# atau untuk MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=username
DB_PASSWORD=password
```

### 2.5 Jalankan Migrasi
```bash
# Jalankan migrasi dan seed
php artisan migrate --seed
```

### 2.6 Konfigurasi Storage
```bash
# Buat symbolic link untuk storage
php artisan storage:link
```

## 3. Konfigurasi Tambahan

### 3.1 Email Configuration
```env
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 3.2 Cache Configuration
```bash
# Jalankan perintah untuk cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 4. Struktur Database Penting

### 4.1 Tabel Utama
- `users` - Informasi pengguna dan otentikasi
- `organizations` - Informasi organisasi sekolah
- `posts` - Postingan blog dan informasi
- `activities` - Kegiatan dan event sekolah
- `facilities` - Fasilitas sekolah
- `students` - Data siswa
- `teachers` - Data guru
- `registrations` - Pendaftaran ke organisasi
- `security_logs` - Log aktivitas keamanan
- `email_otps` - OTP verifikasi email

### 4.2 Tabel Role & Permission
- `roles` - Definisi role
- `permissions` - Definisi permission
- `model_has_roles` - Relasi user ke role (Spatie)
- `role_user` - Relasi user ke role (legacy)
- `abilities` - Kemampuan spesifik
- `ability_role` - Relasi ability ke role

## 5. Konfigurasi Keamanan

### 5.1 Enkripsi Data
Sistem otomatis mengenkripsi data sensitif:
- Phone numbers
- Address
- NIK (Nomor Induk Kependudukan)
- NIS (Nomor Induk Siswa)

### 5.2 Proteksi Rate Limiting
- Login: 10 percobaan per menit
- OTP: 5 percobaan per menit
- Password reset: 3 percobaan per menit
- Profile update: 10 percobaan per menit

### 5.3 Session Security
- Validasi session secara berkala
- Deteksi perubahan IP
- Waktu session terbatas

## 6. Fitur Admin Panel

### 6.1 Akses
- URL: `/admin`
- Hanya untuk pengguna dengan role admin
- Otentikasi standar Laravel

### 6.2 Modul Admin
- Dashboard statistik
- Manajemen pengguna
- Manajemen konten (postingan, organisasi, kegiatan)
- Manajemen siswa dan guru
- Manajemen fasilitas
- Manajemen pendaftaran
- Audit keamanan
- Pengaturan sistem

## 7. API & Integrasi

### 7.1 Sanctum API
Sistem mendukung API token berbasis Sanctum:
```php
// Contoh penggunaan Sanctum
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
}
```

### 7.2 Response Cache
Sistem menggunakan Spatie ResponseCache untuk:
- Halaman statis
- Halaman profil
- Halaman organisasi
- Otomatis invalidasi saat data berubah

## 8. Testing

### 8.1 Jalankan Tests
```bash
# Jalankan semua test
php artisan test

# Jalankan test spesifik
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit
```

### 8.2 Jenis Test
- Feature Tests: Testing request/response
- Unit Tests: Testing individual functions
- Security Tests: Testing keamanan access control

## 9. Deployment

### 9.1 Production Deployment
```bash
# Install dependencies tanpa dev
composer install --optimize-autoloader --no-dev

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set production environment
APP_ENV=production
```

### 9.2 Web Server Configuration
#### Apache (.htaccess)
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

#### Nginx
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

## 10. Troubleshooting

### 10.1 Masalah Umum
1. **SQLSTATE[HY000] [1406] Data too long**: Periksa ukuran field di database
2. **Maximum execution time exceeded**: Tambahkan `set_time_limit(0)` di command
3. **Permission denied**: Pastikan folder storage dan bootstrap/cache writable

### 10.2 Debug Mode
- Set `APP_DEBUG=true` di file `.env`
- Gunakan `php artisan serve` untuk development
- Log error di `storage/logs/laravel.log`

File ini memberikan panduan lengkap untuk menginstal, mengonfigurasi, dan mengelola sistem.