<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organization;

class OrganizationSeeder extends Seeder
{
    public function run()
    {
        $organizations = [
            [
                'name' => 'OSIS NU',
                'type' => 'Organisasi Siswa',
                'tagline' => 'Memimpin dengan Akhlakul Karimah',
                'description' => 'Organisasi Siswa Intra Sekolah yang menaungi seluruh kegiatan kesiswaan dengan semangat nilai-nilai Nahdlatul Ulama.',
                'icon' => 'bi-building',
                'image' => '/images/organizations/osis.svg',
                'color' => 'primary',
                'tags' => ['Kepemimpinan', 'Event Organizer', 'Dakwah'],
                'programs' => [
                    'Penyelenggaraan MPLS Islami',
                    'Festival Seni Budaya Islam', 
                    'Bakti Sosial Ramadhan',
                    'Pelatihan Leadership Siswa',
                    'Koordinasi Kegiatan Ekstrakurikuler',
                    'Program Mentoring Adik Kelas'
                ],
                'leadership' => [
                    ['name' => 'Ahmad Fauzi', 'position' => 'Ketua OSIS'],
                    ['name' => 'Siti Aminah', 'position' => 'Wakil Ketua'],
                    ['name' => 'Muhammad Rizki', 'position' => 'Sekretaris'],
                    ['name' => 'Fatimah Zahra', 'position' => 'Bendahara'],
                    ['name' => 'Ali Akbar', 'position' => 'Koordinator Bidang Keagamaan'],
                    ['name' => 'Khadijah Sari', 'position' => 'Koordinator Bidang Sosial']
                ],
                'email' => 'osis.manu@gmail.com',
                'phone' => '0812-3456-7890',
                'location' => 'Ruang OSIS, Lantai 2',
                'order' => 1
            ],
            [
                'name' => 'IPNU',
                'type' => 'Pelajar NU Putra',
                'tagline' => 'Santri Milenial Berakhlak Mulia',
                'description' => 'Ikatan Pelajar Nahdlatul Ulama yang membina pelajar putra dengan nilai-nilai ahlussunnah wal jamaah.',
                'icon' => 'bi-mortarboard',
                'image' => '/images/organizations/ipnu.svg',
                'color' => 'success',
                'tags' => ['Kajian Islam', 'Leadership', 'Aswaja'],
                'programs' => [
                    'Kajian Kitab Kuning Mingguan',
                    'Pelatihan Kepemimpinan Santri',
                    'Diskusi Keislaman Kontemporer',
                    'Pengkaderan Organisasi NU',
                    'Bakti Sosial Masyarakat',
                    'Seminar Aswaja dan Kebangsaan'
                ],
                'leadership' => [
                    ['name' => 'Habib Umar', 'position' => 'Ketua IPNU'],
                    ['name' => 'Zainul Arifin', 'position' => 'Wakil Ketua'],
                    ['name' => 'Syamsul Hadi', 'position' => 'Sekretaris'],
                    ['name' => 'Abdurrahman', 'position' => 'Bendahara'],
                    ['name' => 'Fachrul Rozi', 'position' => 'Koordinator Kajian']
                ],
                'email' => 'ipnu.manu@gmail.com',
                'phone' => '0813-4567-8901',
                'location' => 'Ruang IPNU, Gedung Utama',
                'order' => 2
            ],
            [
                'name' => 'IPPNU',
                'type' => 'Pelajar NU Putri',
                'tagline' => 'Santriwati Berprestasi Berakhlak Mulia',
                'description' => 'Ikatan Pelajar Putri Nahdlatul Ulama yang memberdayakan pelajar putri dalam bidang sosial, keagamaan, dan pengembangan keterampilan.',
                'icon' => 'bi-person-hearts',
                'image' => '/images/organizations/ippnu.svg',
                'color' => 'info',
                'tags' => ['Pemberdayaan', 'Keagamaan', 'Keterampilan'],
                'programs' => [
                    'Kajian Fiqh Perempuan',
                    'Pelatihan Keterampilan Hidup',
                    'Workshop Kerajinan Tangan',
                    'Pemberdayaan Ekonomi Kreatif',
                    'Seminar Kesehatan Reproduksi Islami',
                    'Program Literasi Digital Santri'
                ],
                'leadership' => [
                    ['name' => 'Khadijah Nur', 'position' => 'Ketua IPPNU'],
                    ['name' => 'Aisyah Putri', 'position' => 'Wakil Ketua'],
                    ['name' => 'Maryam Salsabila', 'position' => 'Sekretaris'],
                    ['name' => 'Zainab Husna', 'position' => 'Bendahara'],
                    ['name' => 'Hafsah Amalia', 'position' => 'Koordinator Keterampilan']
                ],
                'email' => 'ippnu.manu@gmail.com',
                'phone' => '0814-5678-9012',
                'location' => 'Ruang IPPNU, Gedung Putri',
                'order' => 3
            ],
            [
                'name' => 'Pagar Nusa',
                'type' => 'Pencak Silat NU',
                'tagline' => 'Seni Bela Diri Berjiwa Spiritual',
                'description' => 'Seni bela diri tradisional yang memadukan kekuatan fisik dengan nilai spiritual Islam.',
                'icon' => 'bi-shield-fill-check',
                'image' => '/images/organizations/pagar-nusa.svg',
                'color' => 'warning',
                'tags' => ['Bela Diri', 'Spiritual', 'Tradisional'],
                'programs' => [
                    'Latihan Rutin Pencak Silat',
                    'Kompetisi Antar Sekolah',
                    'Pelatihan Jurus Tradisional NU',
                    'Ujian Kenaikan Tingkat',
                    'Pertunjukan Seni Bela Diri',
                    'Pembinaan Mental Spiritual'
                ],
                'leadership' => [
                    ['name' => 'Bayu Pratama', 'position' => 'Ketua Pagar Nusa'],
                    ['name' => 'Dimas Saputra', 'position' => 'Wakil Ketua'],
                    ['name' => 'Arif Budiman', 'position' => 'Pelatih Utama'],
                    ['name' => 'Yoga Pratama', 'position' => 'Koordinator Latihan'],
                    ['name' => 'Eko Susanto', 'position' => 'Sekretaris']
                ],
                'email' => 'pagarnusa.manu@gmail.com',
                'phone' => '0815-6789-0123',
                'location' => 'Lapangan Olahraga',
                'order' => 4
            ],
            [
                'name' => 'Banser Pelajar',
                'type' => 'Barisan Ansor',
                'tagline' => 'Disiplin, Berani, Mengabdi',
                'description' => 'Melatih kedisiplinan, keberanian, dan semangat pengabdian kepada agama dan bangsa.',
                'icon' => 'bi-shield-shaded',
                'image' => '/images/organizations/banser.svg',
                'color' => 'danger',
                'tags' => ['Kedisiplinan', 'Pengabdian', 'Nasionalisme'],
                'programs' => [
                    'Pelatihan Baris Berbaris',
                    'Pengamanan Kegiatan Sekolah',
                    'Upacara Bendera Mingguan',
                    'Pelatihan Kepemimpinan Militer',
                    'Bakti Sosial Kemanusiaan',
                    'Pendidikan Karakter Nasionalisme'
                ],
                'leadership' => [
                    ['name' => 'Galih Pratama', 'position' => 'Komandan Banser'],
                    ['name' => 'Hendra Kusuma', 'position' => 'Wakil Komandan'],
                    ['name' => 'Rizky Firmansyah', 'position' => 'Kepala Staf'],
                    ['name' => 'Andi Setiawan', 'position' => 'Koordinator Keamanan'],
                    ['name' => 'Budi Santoso', 'position' => 'Koordinator Upacara']
                ],
                'email' => 'banser.manu@gmail.com',
                'phone' => '0816-7890-1234',
                'location' => 'Pos Keamanan Sekolah',
                'order' => 5
            ],
            [
                'name' => 'Jam\'iyyah Qurra',
                'type' => 'Tilawah Al-Qur\'an',
                'tagline' => 'Menyuarakan Keindahan Al-Qur\'an',
                'description' => 'Mengembangkan kemampuan membaca, menghafal, dan memahami Al-Qur\'an dengan baik.',
                'icon' => 'bi-book',
                'image' => '/images/organizations/qurra.svg',
                'color' => 'secondary',
                'tags' => ['Tilawah', 'Tahfidz', 'Tafsir'],
                'programs' => [
                    'Pelatihan Tilawah dan Tahsin',
                    'Kompetisi Qira\'ah Antar Kelas',
                    'Program Tahfidz Al-Qur\'an',
                    'Kajian Tafsir Mingguan',
                    'Pelatihan Qori dan Qoriah',
                    'Festival Seni Islami'
                ],
                'leadership' => [
                    ['name' => 'Khalil Rahman', 'position' => 'Ketua Jam\'iyyah Qurra'],
                    ['name' => 'Laila Maghfiroh', 'position' => 'Wakil Ketua'],
                    ['name' => 'Ahmad Taufiq', 'position' => 'Koordinator Tilawah'],
                    ['name' => 'Siti Maryam', 'position' => 'Koordinator Tahfidz'],
                    ['name' => 'Usman Hakim', 'position' => 'Koordinator Kajian']
                ],
                'email' => 'qurra.manu@gmail.com',
                'phone' => '0817-8901-2345',
                'location' => 'Musholla Sekolah',
                'order' => 6
            ]
        ];

        foreach ($organizations as $org) {
            Organization::updateOrCreate(
                ['name' => $org['name']],
                $org
            );
        }
    }
}
