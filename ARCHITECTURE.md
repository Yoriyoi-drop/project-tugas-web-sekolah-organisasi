# Arsitektur dan Komponen Sistem

## 1. Ringkasan Arsitektur

### 1.1 Model Arsitektur
Sistem ini menggunakan arsitektur Model-View-Controller (MVC) dengan pendekatan modern:
- **Model**: Eloquent ORM dengan relasi dan scope
- **View**: Blade templates dengan Bootstrap 5
- **Controller**: RESTful controllers dengan middleware

### 1.2 Komponen Inti
1. **Authentication System**: Login, registrasi, OTP, 2FA
2. **Authorization System**: Role & permission (Spatie + legacy)
3. **Security System**: Enkripsi data, logging, rate limiting
4. **Content Management**: Post, organization, activity management
5. **Profile Management**: User profile, avatar, settings
6. **Admin Panel**: Dashboard dan manajemen sistem

## 2. Komponen Detil

### 2.1 Sistem Otentikasi
```
├── LoginController
│   ├── showLoginForm() - Tampilkan form login
│   ├── login() - Proses login dengan proteksi brute force
│   └── logout() - Proses logout
├── RegisterController
│   ├── showRegistrationForm() - Tampilkan form pendaftaran
│   └── register() - Proses pendaftaran dengan validasi NIK/NIS
├── OtpController
│   ├── show() - Tampilkan form verifikasi OTP
│   └── verify() - Proses verifikasi OTP dengan rate limiting
└── TwoFactorController
    ├── show() - Tampilkan status 2FA
    ├── enable() - Aktifkan 2FA
    └── disable() - Nonaktifkan 2FA
```

### 2.2 Sistem Profile
```
├── ProfileController
│   ├── show() - Tampilkan profil pengguna
│   ├── edit() - Tampilkan form edit profil
│   ├── update() - Update profil pengguna
│   ├── editPassword() - Tampilkan form edit password
│   ├── updatePassword() - Update password dengan verifikasi
│   ├── requestPasswordChange() - Request perubahan password
│   ├── showVerifyPassword() - Tampilkan form verifikasi
│   └── verifyPasswordChange() - Proses verifikasi perubahan password
└── AvatarController
    ├── upload() - Upload avatar dengan validasi
    └── delete() - Hapus avatar
```

### 2.3 Sistem Keamanan
```
├── SecurityService
│   ├── logActivity() - Log aktivitas keamanan
│   ├── logFailedLogin() - Log login gagal
│   ├── logOtpGenerate()/logOtpVerify() - Log OTP
│   ├── maskSensitiveData() - Mask data sensitif
│   ├── encrypt/decryptSensitiveField() - Enkripsi data
│   ├── validateSecureSession() - Validasi session
│   └── detectSuspiciousActivity() - Deteksi aktivitas mencurigakan
├── SecurityLog Model
│   └── Model untuk menyimpan log keamanan
└── SecurityAuditRepository
    ├── getPaginatedLogs() - Dapatkan log audit
    ├── getSummaryStats() - Dapatkan statistik ringkasan
    ├── getEventTypes() - Dapatkan jenis event
    └── getRecentHighRiskEvents() - Dapatkan event risiko tinggi
```

### 2.4 Sistem Role & Permission
```
├── User Model (menggunakan HasRoles trait dari Spatie)
│   ├── hasRole() - Cek role dengan fallback ke legacy
│   ├── hasAbility() - Cek kemampuan dengan fallback ke legacy
│   ├── hasAnyRole() - Cek apakah punya salah satu role
│   └── syncLegacyRolesToSpatie() - Sinkronisasi role legacy ke Spatie
├── Spatie\Permission\Models
│   ├── Role - Model role
│   ├── Permission - Model permission
│   └── Models - Model-model lain dari package
└── Middleware
    ├── AdminMiddleware - Middleware untuk akses admin
    ├── RoleMiddleware - Middleware untuk cek role
    └── CheckAbility - Middleware untuk cek kemampuan
```

### 2.5 Sistem Cache & Response
```
├── Spatie\ResponseCache
│   └── Otomatis cache response GET
├── Cache invalidation
│   └── Otomatis hapus cache saat data penting berubah
└── Tag-based caching
    └── Pengelompokan cache berdasarkan fungsi
```

## 3. Enkripsi & Keamanan Data

### 3.1 Data Sensitif yang Dienkripsi
- Phone numbers: `$user->phone` disimpan terenkripsi
- Address: `$user->address` disimpan terenkripsi
- NIK: `$user->nik` disimpan terenkripsi + hash untuk unik
- NIS: `$user->nis` disimpan terenkripsi + hash untuk unik

### 3.2 Proses Enkripsi
```php
// Di User model
public function setPhoneAttribute($value): void
{
    $this->attributes['phone'] = $value ? SecurityService::encryptSensitiveField($value) : null;
}

public function getPhoneAttribute($value): ?string
{
    return $value ? SecurityService::decryptSensitiveField($value) : null;
}
```

## 4. Sistem Audit & Logging

### 4.1 Jenis Log Aktivitas
- `login`: Login berhasil
- `logout`: Logout
- `register`: Registrasi baru
- `password_reset`: Reset password
- `email_verify`: Verifikasi email
- `profile_update`: Update profil
- `failed_login`: Login gagal
- `otp_generate`: Pembuatan OTP
- `otp_verify`: Verifikasi OTP berhasil
- `otp_failed`: Verifikasi OTP gagal
- `account_locked`: Akun dikunci
- `account_unlocked`: Akun dibuka

### 4.2 Klasifikasi Risiko
- `low`: Aktivitas normal
- `medium`: Aktivitas yang perlu diperhatikan
- `high`: Aktivitas mencurigakan

## 5. Sistem File & Storage

### 5.1 Struktur Storage
```
storage/
├── app/
│   └── public/
│       ├── avatars/ - Avatar pengguna
│       └── facilities/ - Gambar fasilitas
├── framework/
│   ├── cache/ - Cache sistem
│   └── sessions/ - Session data
└── logs/ - Log aplikasi
```

### 5.2 Public Access
```
public/
├── storage/ -> symlink ke storage/app/public
└── assets/
    ├── css/
    ├── js/
    └── images/
```

## 6. Workflow Penting

### 6.1 Alur Registrasi Lengkap
```
User → Formulir Registrasi → Validasi NIK/NIS → Pembuatan Akun → 
Pengiriman OTP → Verifikasi OTP → Aktivasi Akun → Login Otomatis → 
Dashboard Profil
```

### 6.2 Alur Update Profil
```
User → Edit Profil → Validasi Input → Update Database → 
Log Aktivitas → Tampilkan Sukses → Kembali ke Profil
```

### 6.3 Alur Upload Avatar
```
User → Pilih File → Validasi File → Hapus Avatar Lama → 
Simpan Baru di Storage → Update Database → 
Hapus Cache Terkait → Tampilkan Sukses
```

## 7. API & Integration Points

### 7.1 Event System
- `PostCreated` - Dipicu saat postingan dibuat
- Event custom bisa ditambahkan untuk integrasi

### 7.2 Queue System
- `ProcessAiRequest` - Job untuk pemrosesan permintaan AI
- Queue bisa ditambahkan untuk task berat

## 8. Optimasi & Performance

### 8.1 Database Optimasi
- Eager loading untuk relasi
- Index pada kolom sering digunakan
- Query scope untuk pengambilan data efisien

### 8.2 Response Caching
- Otomatis cache response GET
- Invalidasi otomatis saat data berubah
- Tagging untuk pengelompokan cache

## 9. Testing Strategy

### 9.1 Jenis Testing
- **Unit Tests**: Testing fungsi individual
- **Feature Tests**: Testing request/response flow
- **Security Tests**: Testing access control
- **Integration Tests**: Testing integrasi antar komponen

### 9.2 Coverage Areas
- Authentication flow
- Authorization rules
- Data validation
- Security measures
- Critical business logic

## 10. Scalability Considerations

### 10.1 Horizontal Scaling
- Database connection pooling
- Redis as caching layer
- Queue system for heavy tasks

### 10.2 Performance Monitoring
- Query optimization
- Cache hit ratios
- Response time monitoring
- Security event monitoring

File ini memberikan gambaran menyeluruh tentang arsitektur dan komponen sistem.