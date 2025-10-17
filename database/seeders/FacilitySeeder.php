<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facility;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        $facilities = [
            ['icon' => 'bi-building', 'title' => 'Gedung Pembelajaran', 'description' => '24 ruang kelas ber-AC dengan fasilitas multimedia dan proyektor', 'order' => 1],
            ['icon' => 'bi-book', 'title' => 'Perpustakaan Digital', 'description' => 'Koleksi 15.000+ buku fisik dan akses e-library dengan ribuan jurnal', 'order' => 2],
            ['icon' => 'bi-flask', 'title' => 'Laboratorium', 'description' => 'Lab IPA, Komputer, dan Bahasa dengan peralatan modern', 'order' => 3],
            ['icon' => 'bi-house-heart', 'title' => 'Masjid Sekolah', 'description' => 'Masjid berkapasitas 1000 jamaah dengan fasilitas lengkap', 'order' => 4],
            ['icon' => 'bi-trophy', 'title' => 'Fasilitas Olahraga', 'description' => 'Lapangan basket, voli, futsal, dan aula olahraga', 'order' => 5],
            ['icon' => 'bi-house', 'title' => 'Asrama Santri', 'description' => 'Asrama putra dan putri dengan kapasitas 400 santri', 'order' => 6]
        ];

        foreach ($facilities as $facility) {
            Facility::create($facility);
        }
    }
}