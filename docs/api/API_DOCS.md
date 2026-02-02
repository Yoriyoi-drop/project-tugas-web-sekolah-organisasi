# Dokumentasi Sistem Madrasah Aliyah Nusantara

## 1. Arsitektur Sistem

### 1.1 Teknologi Utama
- **Backend**: Laravel 12
- **Database**: SQLite (default), MySQL/PostgreSQL (opsional)
- **Frontend**: Bootstrap 5, JavaScript ES6
- **CSS Assets**: Google Fonts, Bootstrap Icons, Font Awesome (offline-first approach)
- **Framework Tambahan**: Spatie Laravel Permission, Laravel Sanctum, Livewire

### 1.2 Struktur Direktori Penting
```
├── app/                    # Core application logic
│   ├── Console/           # Artisan commands
│   ├── Http/              # Controllers, Middleware, Requests
│   │   ├── Controllers/
│   │   │   ├── Admin/    # Admin panel controllers
│   │   │   └── Auth/     # Authentication controllers
│   │   ├── Middleware/    # Security and access control
│   │   └── Requests/      # Form validation
│   ├── Models/            # Eloquent models
│   ├── Services/          # Business logic services
│   └── Repositories/      # Data access patterns
├── config/                # Configuration files
├── database/              # Migrations, seeds, factories
├── public/                # Public assets
│   ├── css/              # CSS assets (offline-first approach)
│   │   ├── bootstrap-icons-npm/ # Bootstrap Icons CSS and fonts (from npm)
│   │   ├── fontawesome/     # Font Awesome CSS
│   │   ├── fonts/           # Google Fonts files
│   │   ├── webfonts/        # Font Awesome font files
│   │   └── bootstrap.min.css # Optimized Bootstrap CSS
├── resources/             # Views, assets
└── storage/               # File storage
```

## 2. Sistem Otentikasi

### 2.1 Proses Login
```
User → LoginForm → [Validate Email/Password] → [Check Account Lock Status] → 
[Auth::attempt()] → [Session Regenerate] → [Redirect to Dashboard]
```

### 2.2 Proteksi Akun
- Maksimal 5 percobaan login gagal
- Otomatis kunci akun 30 menit setelah batas terlampaui
- Pemeriksaan IP change detection

### 2.3 Sistem OTP
- Enkripsi kode OTP sebelum disimpan
- Maksimal 10 menit berlaku
- Pembatasan jumlah percobaan verifikasi

## 3. Sistem Role & Permission

### 3.1 Dua Sistem Berjalan Berdampingan
1. **Spatie Laravel Permission** (sistem utama)
2. **Sistem Legacy** (untuk backward compatibility)

### 3.2 Implementasi
```php
// Contoh pengecekan role
if ($user->hasRole('admin')) {
    // Akses admin
}

// Contoh pengecekan ability
if ($user->hasAbility('manage_posts')) {
    // Bisa mengelola postingan
}
```

## 4. Sistem Keamanan

### 4.1 Enkripsi Data Sensitif
- Phone dan address dienkripsi sebelum disimpan
- NIK dan NIS dienkripsi dan di-hash untuk validasi unik

### 4.2 Logging Keamanan
- Setiap aksi penting dicatat di SecurityLog
- Klasifikasi risiko (rendah, sedang, tinggi)
- Deteksi aktivitas mencurigakan

### 4.3 Rate Limiting
- Pembatasan permintaan API
- Proteksi brute force
- Pemblokiran otomatis untuk aktivitas mencurigakan

## 5. API Endpoints

### 5.1 Public Endpoints
```
GET /                 - Halaman utama
GET /beranda          - Beranda sekolah
GET /organisasi       - Daftar organisasi
GET /organisasi/{id}  - Detail organisasi
GET /blog            - Daftar postingan blog
GET /kegiatan        - Daftar kegiatan
GET /fasilitas       - Daftar fasilitas
GET /fasilitas/{id}  - Detail fasilitas
GET /tentang         - Tentang sekolah
GET /kontak          - Form kontak
POST /kontak         - Kirim pesan kontak
```

### 5.2 Authenticated Endpoints
```
GET /profile              - Lihat profil
GET /profile/edit         - Edit profil
PUT /profile              - Update profil
POST /profile/request-password-change  - Request ganti password
GET /profile/verify-password        - Verifikasi password
POST /profile/verify-password       - Verifikasi dan ganti password
GET /profile/password               - Edit password
PUT /profile/password               - Update password
POST /profile/password/code         - Kirim kode verifikasi
POST /avatar/upload               - Upload avatar
GET /avatar/delete                - Hapus avatar
GET /2fa                        - Lihat 2FA
POST /2fa/enable                - Aktifkan 2FA
POST /2fa/disable               - Nonaktifkan 2FA
```

### 5.3 Admin Endpoints
```
GET /admin                    - Dashboard admin
GET/POST/PUT/DELETE /admin/posts           - Manajemen postingan
GET/POST/PUT/DELETE /admin/organizations   - Manajemen organisasi
GET/POST/PUT/DELETE /admin/activities      - Manajemen kegiatan
GET/POST/PUT/DELETE /admin/statistics      - Manajemen statistik
GET/POST/PUT/DELETE /admin/students        - Manajemen siswa
GET/POST/PUT/DELETE /admin/teachers        - Manajemen guru
GET/POST/PUT/DELETE /admin/facilities      - Manajemen fasilitas
GET/POST/PUT/DELETE /admin/messages        - Manajemen pesan
GET /admin/settings             - Pengaturan sistem
PUT /admin/settings             - Update pengaturan
GET /admin/registrations        - Manajemen pendaftaran
PATCH /admin/registrations/{id}/status  - Update status pendaftaran
GET/POST/PUT/DELETE /admin/users           - Manajemen pengguna
GET /admin/security/audit       - Audit keamanan
GET /admin/security/export      - Ekspor audit
```

## 6. Alur Bisnis Utama

### 6.1 Alur Pendaftaran Organisasi
1. User mengakses `/daftar/{organization}`
2. Form pendaftaran ditampilkan
3. Validasi data dilakukan
4. Data disimpan ke tabel `registrations`
5. Admin menerima notifikasi
6. Admin menyetujui atau menolak pendaftaran

### 6.2 Alur Update Profil
1. User mengakses `/profile/edit`
2. Form update ditampilkan
3. Validasi input dilakukan
4. Data profil diperbarui
5. Log aktivitas disimpan

### 6.3 Alur Upload Avatar
1. User memilih file gambar
2. Validasi file (tipe, ukuran) dilakukan
3. File disimpan di storage
4. Referensi di database diperbarui
5. Cache dihapus jika perlu

## 7. Sistem Cache & Performa

### 7.1 Response Cache
- Menggunakan Spatie ResponseCache
- Otomatis invalidasi saat data penting berubah
- Tag-based caching untuk efisiensi

### 7.2 Database Cache
- Menggunakan Laravel cache driver
- Cache query dan model
- Strategi cache yang efisien

## 8. Error Handling & Logging

### 8.1 Error Handling
- Exception handling global
- Logging error ke file
- Notifikasi error penting

### 8.2 Audit Logging
- Setiap aktivitas penting dicatat
- Klasifikasi berdasarkan risiko
- Penyimpanan terpusat di SecurityLog

## 9. Deployment & Konfigurasi

### 9.1 Lingkungan Produksi
- Konfigurasi environment
- Setup database
- Konfigurasi web server
- SSL certificate

### 9.2 Lingkungan Pengembangan
- PHP >= 8.2
- Composer dependencies
- Database (SQLite default)
- Node.js untuk asset compilation (opsional)

File ini berfungsi sebagai referensi lengkap untuk memahami dan mengembangkan sistem lebih lanjut.