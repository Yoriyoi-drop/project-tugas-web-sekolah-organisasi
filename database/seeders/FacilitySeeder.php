<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facility;

class FacilitySeeder extends Seeder
{
    public function run()
    {
        $facilities = [
            [
                'name' => 'Masjid Al-Hikmah',
                'description' => 'Masjid utama madrasah dengan kapasitas besar untuk sholat berjamaah, kajian keagamaan, dan kegiatan spiritual lainnya.',
                'category' => 'Masjid',
                'capacity' => 500,
                'location' => 'Tengah Kompleks Sekolah',
                'status' => 'active',
                'features' => ['Sound System', 'AC', 'Karpet', 'Mihrab', 'Mimbar', 'Tempat Wudhu'],
                'contact_person' => 'Ustadz Ahmad Fauzi',
                'operating_hours' => '24 Jam'
            ],
            [
                'name' => 'Perpustakaan Maktabah',
                'description' => 'Perpustakaan lengkap dengan koleksi buku agama, umum, dan digital untuk mendukung pembelajaran siswa.',
                'category' => 'Perpustakaan',
                'capacity' => 100,
                'location' => 'Lantai 2, Gedung Utama',
                'status' => 'active',
                'features' => ['WiFi', 'AC', 'Komputer', 'Ruang Baca', 'Koleksi Digital', 'CCTV'],
                'contact_person' => 'Ibu Siti Aminah, S.Pd',
                'operating_hours' => '07:00 - 16:00'
            ],
            [
                'name' => 'Laboratorium IPA',
                'description' => 'Laboratorium lengkap untuk praktikum Fisika, Kimia, dan Biologi dengan peralatan modern.',
                'category' => 'Laboratorium',
                'capacity' => 40,
                'location' => 'Lantai 3, Gedung Sains',
                'status' => 'active',
                'features' => ['Mikroskop', 'Alat Praktikum', 'Fume Hood', 'Wastafel', 'Lemari Asam', 'Proyektor'],
                'contact_person' => 'Bapak Dr. Muhammad Rizki',
                'operating_hours' => '08:00 - 15:00'
            ],
            [
                'name' => 'Laboratorium Komputer',
                'description' => 'Lab komputer dengan 30 unit PC untuk pembelajaran TIK dan multimedia.',
                'category' => 'Laboratorium',
                'capacity' => 30,
                'location' => 'Lantai 2, Gedung TIK',
                'status' => 'active',
                'features' => ['30 Unit PC', 'Proyektor', 'AC', 'WiFi', 'Software Pembelajaran', 'CCTV'],
                'contact_person' => 'Bapak Andi Setiawan, S.Kom',
                'operating_hours' => '07:00 - 16:00'
            ],
            [
                'name' => 'Aula Serbaguna',
                'description' => 'Ruang serbaguna untuk acara besar, seminar, dan kegiatan sekolah lainnya.',
                'category' => 'Ruang Kelas',
                'capacity' => 300,
                'location' => 'Lantai 1, Gedung Utama',
                'status' => 'active',
                'features' => ['Sound System', 'Proyektor', 'AC', 'Panggung', 'Kursi Lipat', 'Lighting'],
                'contact_person' => 'Bapak Hendra Kusuma',
                'operating_hours' => '08:00 - 21:00'
            ],
            [
                'name' => 'Lapangan Olahraga',
                'description' => 'Lapangan multifungsi untuk berbagai cabang olahraga dan kegiatan fisik.',
                'category' => 'Olahraga',
                'capacity' => 200,
                'location' => 'Belakang Sekolah',
                'status' => 'active',
                'features' => ['Lapangan Basket', 'Lapangan Voli', 'Lapangan Futsal', 'Tribun', 'Lampu Penerangan'],
                'contact_person' => 'Bapak Yoga Pratama, S.Pd',
                'operating_hours' => '06:00 - 18:00'
            ],
            [
                'name' => 'Kantin Sehat',
                'description' => 'Kantin sekolah yang menyediakan makanan dan minuman sehat untuk siswa dan guru.',
                'category' => 'Kantin',
                'capacity' => 80,
                'location' => 'Samping Lapangan',
                'status' => 'active',
                'features' => ['Meja Makan', 'Kursi', 'Wastafel', 'Kulkas', 'Kompor Gas', 'Ventilasi'],
                'contact_person' => 'Ibu Fatimah Zahra',
                'operating_hours' => '06:30 - 15:00'
            ],
            [
                'name' => 'Asrama Putra',
                'description' => 'Asrama untuk siswa putra dengan fasilitas lengkap dan nyaman.',
                'category' => 'Asrama',
                'capacity' => 120,
                'location' => 'Gedung Asrama Putra',
                'status' => 'active',
                'features' => ['Kamar Tidur', 'Kamar Mandi', 'Ruang Belajar', 'Dapur', 'Laundry', 'WiFi'],
                'contact_person' => 'Ustadz Abdul Rahman',
                'operating_hours' => '24 Jam'
            ],
            [
                'name' => 'Asrama Putri',
                'description' => 'Asrama untuk siswa putri dengan pengawasan ketat dan fasilitas memadai.',
                'category' => 'Asrama',
                'capacity' => 100,
                'location' => 'Gedung Asrama Putri',
                'status' => 'active',
                'features' => ['Kamar Tidur', 'Kamar Mandi', 'Ruang Belajar', 'Dapur', 'Laundry', 'WiFi'],
                'contact_person' => 'Ustadzah Khadijah Nur',
                'operating_hours' => '24 Jam'
            ],
            [
                'name' => 'Ruang UKS',
                'description' => 'Unit Kesehatan Sekolah untuk pertolongan pertama dan pemeriksaan kesehatan.',
                'category' => 'Lainnya',
                'capacity' => 10,
                'location' => 'Lantai 1, Dekat Kantor Guru',
                'status' => 'active',
                'features' => ['Tempat Tidur', 'Kotak P3K', 'Timbangan', 'Tensimeter', 'Lemari Obat', 'AC'],
                'contact_person' => 'Ibu dr. Sari Dewi',
                'operating_hours' => '07:00 - 15:00'
            ]
        ];

        foreach ($facilities as $facility) {
            Facility::updateOrCreate(
                ['name' => $facility['name']],
                $facility
            );
        }
    }
}