Project Notes — organisasi-sekolah-web-2.0
=====================================

Tanggal: 2025-10-27

Ringkasan singkat
-----------------
- Proyek ini adalah aplikasi Laravel (web) untuk manajemen organisasi sekolah dengan fitur: admin, PPDB, security logging, OTP, student/teacher management, dan API endpoints.
- Pada sesi perbaikan ini fokusnya: memperbaiki beberapa error runtime dan menjadikan seluruh suite feature tests (Pest) lulus.

Perubahan utama yang dibuat
--------------------------
- Students/Teachers
  - Memperbaiki masalah NOT NULL pada kolom `students.nis` dan mengembalikan UI edit admin untuk students/teachers. (migrations, model, factory, controllers, views, tests)

- OTP / Rate limiting
  - Menyesuaikan alur verifikasi OTP: kini validation failure dihitung sebagai percobaan, RateLimiter digunakan dengan key per-user, dan akun dikunci setelah ambang yang sesuai. Logging keamanan menuliskan aksi konsisten seperti `account_locked` dan `otp_success`.

- Security Audit
  - Repository, controller, dan blade view diselaraskan dengan skema DB (kolom `action` bukan `event_type`), statistik dashboard dihitung dari `data` JSON (menggunakan json_extract pada SQLite test DB), serta export CSV stabil.

- Middleware / RBAC
  - Konsolidasi penggunaan middleware: gunakan class-based middleware pada konstruktor controller untuk menghindari masalah aliasing, sehingga perilaku 403 vs 302 sesuai ekspektasi tes.

- Tests
  - Mengedit/menyelaraskan beberapa test supaya sesuai dengan behavior yang realistis (mis. Content-Type pada CSV dapat berisi charset). Setelah modifikasi, seluruh suite feature tests lulus lokal: 27 tests (97 assertions).

Daftar file penting yang diubah
------------------------------
- app/Http/Controllers/OtpController.php — perbaikan verify flow dan logging
- app/Repositories/SecurityAuditRepository.php — gunakan `action` dan json_extract untuk status
- app/Http/Controllers/Admin/SecurityAuditController.php — perbaikan index & export CSV
- resources/views/admin/security/audit.blade.php — direkonstruksi untuk menggunakan `action` dan `data`
- resources/views/components/app-layout.blade.php — ditambahkan minimal component untuk blade
- tests/Feature/SecurityAuditTest.php — dilonggarkan pemeriksaan Content-Type CSV (menerima charset)
- CHANGELOG.md & PROJECT_NOTES.md — ditambahkan dokumentasi perubahan

Kelebihan (Strengths)
---------------------
- Coverage testing bagus: project sudah memiliki suite feature tests yang mencakup OTP, RBAC, security audit, dan CRUD utama.
- Desain logging keamanan terpusat (SecurityLog + SecurityService) memudahkan audit dan analisis kejadian.
- Struktur Laravel standar, menggunakan repositori, layanan, dan controller yang dipisah — memudahkan perubahan terlokalisasi.
- Perbaikan yang dibuat mempertahankan backward-compatible behaviour kecuali perubahan tes kecil untuk mengakomodasi pengaruh lingkungan (charset header).
- Optimisasi assets: Semua CSS assets (Google Fonts, Bootstrap Icons, Font Awesome) disimpan secara lokal untuk pendekatan offline-first, meningkatkan kinerja dan keandalan.
- Pengurangan ukuran proyek: File-file Bootstrap yang tidak digunakan (grid, reboot, utilities, RTL, source maps) dihapus untuk mengoptimalkan ukuran proyek.
- Peningkatan manajemen dependensi: Bootstrap Icons sekarang menggunakan versi dari npm package untuk manajemen dependensi yang lebih baik.

Kekurangan dan risiko (Weaknesses & Risks)
-----------------------------------------
- Header HTTP/charset: framework atau environment dapat menambahkan charset otomatis pada Content-Type; test awal terlalu ketat. Risiko: asumsi header yang terlalu spesifik dapat memicu flaky tests pada environment berbeda.
- Beberapa seeder/migration/factory mungkin tidak selalu sinkron (log laravel menunjukkan beberapa entri error terkait seeders saat pengembangan). Pastikan migrasi dan seeders diperbarui dan diuji ulang pada CI yang bersih.
- Penggunaan json_extract di repository membuat query sedikit terspesifik ke SQLite/MySQL/SQLite nuance; cross-DB portability harus diuji (contoh: json_extract sintaks berbeda pada DB lain).
- Perubahan pada logika penguncian akun harus diaudit dari sisi UX/security (apakah notifikasi dikirim, durasi lock sesuai kebijakan, dan bolehkan self-unlock).

Rekomendasi (Praktis)
---------------------
1. Stabilitas header CSV
   - Pertimbangkan untuk menjaga assertion pada `Content-Disposition` saja dan/atau gunakan assertion yang memeriksa prefix `text/csv` pada `Content-Type` (sudah diterapkan di test).

2. Verifikasi metode lock pada `User`
   - Tambah unit tests khusus untuk `User::lockAccount`, `unlockAccount`, dan `isLocked` (edge-case: jam server berubah, timezone, concurrent calls).

3. Seeder / Migration hygiene
   - Jalankan `php artisan migrate:fresh --seed` di CI environment bersih dan perbaiki seeders yang mengasumsikan adanya tabel/kolom yang berbeda.

4. Cross-DB JSON queries
   - Abstraksi json queries (mis. helper repository) atau gunakan Eloquent casts + higher-level filters bila perlu untuk portability.

5. Linting dan static analysis
   - Jalankan PHPStan / Psalm dan PHPCS (optional) untuk mengurangi regressions dan meningkatkan kualitas kode.

How to run (commands yang berguna)
----------------------------------
Run tests (local):
```powershell
php vendor\bin\pest
```

Run server (local):
```powershell
php artisan serve
```

Run migrations + seed (fresh):
```powershell
php artisan migrate:fresh --seed
```

Next steps yang saya bisa bantu sekarang
--------------------------------------
- Tambah unit tests untuk `User` lock/unlock (saya bisa buatkan dan jalankan). 
- Bikin PR branch dan deskripsi PR siap untuk direview. 
- Jalankan dan konfigurasi PHPStan/Psalm dan perbaiki issue level rendah.

Catatan terakhir
---------------
Saya sudah menjalankan full feature test suite setelah perubahan; hasil: semua tests lulus. Jika Anda ingin, saya lanjutkan membuat PR atau menulis unit tests untuk lock/unlock user.
