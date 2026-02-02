# Sistem Informasi Madrasah Aliyah Nusantara

## Diagram Alur Pengguna (User Flow)

### 1. Alur Pengunjung Umum
```
[Halaman Utama] 
    ↓
[Tentang Sekolah]
    ↓
[Navigasi ke halaman: Beranda, Organisasi, Blog, Kegiatan, Fasilitas, Tentang, Kontak]
    ↓
[Interaksi dengan konten (membaca, mengirim kontak)]
```

### 2. Alur Pendaftaran & Verifikasi
```
[Akun Baru] 
    ↓
[Formulir Pendaftaran (NIK/NIS wajib)]
    ↓
[Verifikasi OTP]
    ↓
[Aktivasi Akun]
    ↓
[Akses Lengkap]
```

### 3. Alur Pengguna Terdaftar
```
[Login] 
    ↓
[Dashboard Profil]
    ↓
[Update Profil, Ganti Password, 2FA]
    ↓
[Akses Fitur Berdasarkan Role]
```

### 4. Alur Admin
```
[Login Admin] 
    ↓
[Dashboard Admin]
    ↓
[Manajemen: Pengguna, Postingan, Organisasi, Kegiatan, Siswa, Guru, Fasilitas]
    ↓
[Manajemen Keamanan & Audit]
```

## Diagram Alur Sistem (System Workflow)

### 1. Alur Otentikasi & Otorisasi
```
[Permintaan HTTP]
    ↓
[Middleware: Authenticate]
    ↓
[Validasi Kredensial]
    ↓
[Verifikasi Role/Izin]
    ↓
[Akses Diterima/Ditolak]
    ↓
[Respons HTTP]
```

### 2. Alur Pendaftaran Pengguna
```
[Formulir Pendaftaran]
    ↓
[Validasi: NIK/NIS Unik]
    ↓
[Pembuatan Akun Sementara]
    ↓
[Pengiriman OTP]
    ↓
[Verifikasi OTP + Enkripsi Data Sensitif]
    ↓
[Aktivasi Akun]
    ↓
[Login Otomatis]
```

### 3. Alur Upload Avatar
```
[Pemilihan File di Form]
    ↓
[Validasi File (tipe, ukuran)]
    ↓
[Penghapusan Avatar Lama]
    ↓
[Penyimpanan File di Storage]
    ↓
[Pembaruan Referensi di Database]
    ↓
[Pengembalian URL Avatar]
```

### 4. Alur Keamanan & Audit
```
[Setiap Aksi Penting]
    ↓
[Catatan Aktivitas Keamanan]
    ↓
[Pemeriksaan Risiko]
    ↓
[Klasifikasi Risiko (Rendah/Sedang/Tinggi)]
    ↓
[Penyimpanan di Database SecurityLog]
    ↓
[Jika Risiko Tinggi → Notifikasi/Admin]
```

## Struktur Role & Permission

### 1. Role Sistem
- **Administrator**: Akses penuh ke sistem
- **Anggota/Organization Member**: Akses terbatas ke fitur organisasi
- **Pengguna Biasa**: Akses terbatas ke fitur publik dan profil

### 2. Sistem Permission
- Dua sistem: Legacy (`role_user`, `ability_role`) & Spatie Laravel Permission
- Fallback otomatis antara sistem

## Sistem Keamanan

### 1. Enkripsi Data Sensitif
- NIK/NIS dienkripsi menggunakan fungsi encrypt Laravel
- Data disimpan sebagai hash untuk unik

### 2. Proteksi Brute Force
- Rate limiting untuk login/OTP
- Pemblokiran otomatis setelah percobaan gagal

### 3. Manajemen Session
- Validasi session keamanan
- Pemeriksaan IP change detection
- Waktu session terbatas

## Diagram Interaksi Database

### 1. Hubungan Utama
```
User → [role_user/organization_member] → Role
User → SecurityLog (riwayat aktivitas)
User → EmailOtp (verifikasi)
Organization → [organization_student/organization_teacher] → Student/Teacher
```

### 2. Sistem Cache
- Menggunakan Spatie ResponseCache
- Invalidasi otomatis saat data penting berubah
- Tag-based caching untuk efisiensi
```

### 3. Struktur Folder Penting
```
app/
├── Console/
├── Events/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   └── Auth/
│   ├── Middleware/
│   └── Requests/
├── Models/
├── Repositories/
├── Rules/
├── Services/
│   └── SecurityService.php
├── Graphs/ [telah dihapus]
└── Services/
    └── LangGraphService.php [telah dihapus]
├── public/
│   └── css/ [CSS assets offline-first approach]
│       ├── bootstrap-icons-npm/ [Bootstrap Icons CSS dan font files (from npm)]
│       ├── fontawesome/ [Font Awesome CSS file]
│       ├── fonts/ [Google Fonts files]
│       ├── webfonts/ [Font Awesome font files]
│       └── bootstrap.min.css [Optimized Bootstrap CSS - unused files dihapus]
```

File ini memberikan gambaran menyeluruh tentang bagaimana sistem bekerja dan bagaimana pengguna berinteraksi dengan aplikasi.