<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Statistic;

class StatisticSeeder extends Seeder
{
    public function run(): void
    {
        $statistics = [
            ['label' => 'Organisasi', 'value' => '6', 'description' => 'Organisasi', 'order' => 1],
            ['label' => 'Anggota Aktif', 'value' => '500+', 'description' => 'Anggota Aktif', 'order' => 2],
            ['label' => 'Kegiatan/Tahun', 'value' => '50+', 'description' => 'Kegiatan/Tahun', 'order' => 3],
            ['label' => 'Tahun Berdiri', 'value' => '15+', 'description' => 'Tahun Berdiri', 'order' => 4],
        ];

        foreach ($statistics as $stat) {
            Statistic::create($stat);
        }
    }
}