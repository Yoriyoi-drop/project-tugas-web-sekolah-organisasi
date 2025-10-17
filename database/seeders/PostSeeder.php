<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use Carbon\Carbon;

class PostSeeder extends Seeder
{
    public function run()
    {
        $posts = [
            [
                'title' => 'Prestasi Gemilang Siswa MA NU Nusantara di Kompetisi Sains Nasional 2024',
                'excerpt' => 'Alhamdulillah, siswa-siswi MA NU Nusantara kembali menorehkan prestasi membanggakan dengan meraih juara 1 dalam Kompetisi Sains Nasional bidang Matematika dan Fisika...',
                'content' => 'Prestasi gemilang kembali diraih oleh siswa-siswi MA NU Nusantara dalam ajang Kompetisi Sains Nasional 2024. Tim yang terdiri dari Ahmad Fauzi (Matematika) dan Siti Aminah (Fisika) berhasil meraih juara 1 dalam kategori masing-masing.',
                'icon' => 'bi-trophy-fill',
                'category' => 'Prestasi',
                'color' => 'warning',
                'is_featured' => true,
                'published_at' => Carbon::parse('2024-12-15')
            ],
            [
                'title' => 'Peringatan Maulid Nabi Muhammad SAW 1446 H',
                'excerpt' => 'MA NU Nusantara menggelar peringatan Maulid Nabi Muhammad SAW dengan berbagai kegiatan yang menginspirasi...',
                'content' => 'Dalam rangka memperingati Maulid Nabi Muhammad SAW 1446 H, MA NU Nusantara menggelar serangkaian kegiatan yang penuh makna dan inspirasi.',
                'icon' => 'bi-mosque',
                'category' => 'Kegiatan',
                'color' => 'success',
                'published_at' => Carbon::parse('2024-12-12')
            ],
            [
                'title' => 'Tips Sukses Menghadapi Ujian Semester Ganjil',
                'excerpt' => 'Menjelang ujian semester ganjil, penting bagi siswa untuk mempersiapkan diri dengan baik...',
                'content' => 'Ujian semester ganjil akan segera dimulai. Berikut tips sukses yang dapat membantu siswa dalam menghadapi ujian.',
                'icon' => 'bi-mortarboard',
                'category' => 'Akademik',
                'color' => 'info',
                'published_at' => Carbon::parse('2024-12-10')
            ],
            [
                'title' => 'Program Green School: Menjaga Lingkungan Sejak Dini',
                'excerpt' => 'Inisiatif Green School MA NU Nusantara mengajarkan siswa pentingnya menjaga lingkungan...',
                'content' => 'Program Green School yang dicanangkan MA NU Nusantara bertujuan untuk menumbuhkan kesadaran lingkungan pada siswa.',
                'icon' => 'bi-tree',
                'category' => 'Lingkungan',
                'color' => 'success',
                'published_at' => Carbon::parse('2024-12-08')
            ],
            [
                'title' => 'Juara Umum Lomba Tahfidz Tingkat Provinsi',
                'excerpt' => 'Tim Tahfidz MA NU Nusantara berhasil meraih juara umum dalam lomba Tahfidz Al-Quran...',
                'content' => 'Prestasi membanggakan kembali diraih tim Tahfidz MA NU Nusantara dengan meraih juara umum lomba Tahfidz tingkat provinsi.',
                'icon' => 'bi-trophy',
                'category' => 'Prestasi',
                'color' => 'warning',
                'published_at' => Carbon::parse('2024-12-05')
            ],
            [
                'title' => 'Pelantikan Pengurus OSIS Periode 2024-2025',
                'excerpt' => 'Pelantikan pengurus OSIS baru telah dilaksanakan dengan khidmat...',
                'content' => 'Pelantikan pengurus OSIS MA NU Nusantara periode 2024-2025 telah dilaksanakan dengan penuh khidmat dan semangat.',
                'icon' => 'bi-people',
                'category' => 'Organisasi',
                'color' => 'primary',
                'published_at' => Carbon::parse('2024-12-03')
            ]
        ];

        foreach ($posts as $post) {
            Post::create($post);
        }
    }
}
