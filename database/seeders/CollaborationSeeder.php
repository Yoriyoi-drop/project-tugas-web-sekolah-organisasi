<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organization;
use App\Models\OrganizationDiscussion;
use App\Models\OrganizationActivity;
use App\Models\OrganizationAnnouncement;
use App\Models\User;

class CollaborationSeeder extends Seeder
{
    public function run()
    {
        $organizations = Organization::all();
        // Mencari admin user dengan email yang benar sesuai dengan AdminUserSeeder
        $adminUser = User::where('email', 'admin@sekolah.org')
                        ->orWhere('email', 'admin2@sekolah.org')
                        ->orWhere('is_admin', true)
                        ->first();

        foreach ($organizations as $org) {
            // Create sample discussions
            $this->createSampleDiscussions($org, $adminUser);

            // Create sample activities
            $this->createSampleActivities($org, $adminUser);

            // Create sample announcements
            $this->createSampleAnnouncements($org, $adminUser);
        }
        
        // Jika tidak ada admin user ditemukan, beri peringatan
        if (!$adminUser) {
            $this->command->warn('Admin user tidak ditemukan. Pastikan AdminUserSeeder telah dijalankan terlebih dahulu.');
        }
    }
    }

    private function createSampleDiscussions($organization, $adminUser)
    {
        $discussions = [
            [
                'title' => 'Program Kerja Baru ' . $organization->name,
                'content' => 'Selamat pagi semuanya! Mari kita diskusikan program kerja untuk periode ini. Ada beberapa ide yang ingin saya usulkan:\n\n1. Pelatihan Leadership untuk pengurus baru\n2. Bakti sosial ke panti asuhan\n3. Festival seni dan budaya\n\nBagaimana pendapat teman-teman semua? Ada saran atau tambahan?',
                'type' => 'discussion',
                'is_pinned' => true
            ],
            [
                'title' => 'Pertanyaan: Persyaratan Member Baru',
                'content' => 'Kakak-kakak senior, saya mau tanya. Apa saja persyaratan untuk menjadi anggota ' . $organization->name . '? Apakah ada tes khusus atau wawancara? Terima kasih.',
                'type' => 'question',
                'is_pinned' => false
            ],
            [
                'title' => 'Sharing: Pengalaman Mengikuti Kompetisi',
                'content' => 'Tadi kemarin saya ikut kompetisi yang diadakan oleh ' . $organization->name . '. Pengalaman yang sangat berharga! Banyak sekali ilmu yang saya dapat. Buat teman-teman yang belum pernah ikut, jangan takut ya! Semua support di sini.',
                'type' => 'discussion',
                'is_pinned' => false
            ]
        ];

        foreach ($discussions as $data) {
            // Jika admin user tidak ditemukan, lewati pembuatan diskusi
            if (!$adminUser) {
                continue;
            }
            
            $discussion = $organization->discussions()->create([
                'title' => $data['title'],
                'content' => $data['content'],
                'author_id' => $adminUser->id,
                'type' => $data['type'],
                'is_pinned' => $data['is_pinned'],
                'views' => rand(10, 100),
                'reply_count' => rand(0, 5),
                'last_reply_at' => now()->subHours(rand(1, 24))
            ]);

            // Add some tags
            $tags = ['program', 'informasi', 'pengalaman', 'pertanyaan'];
            $discussion->addTag($tags[array_rand($tags)]);
        }
    }

    private function createSampleActivities($organization, $adminUser)
    {
        $activities = [
            [
                'title' => 'Rapat Rutin Bulanan',
                'description' => 'Rapat koordinasi bulanan untuk membahas program kerja dan evaluasi kegiatan yang telah berjalan. Diharapkan semua pengurus hadir.',
                'type' => 'meeting',
                'start_datetime' => now()->addDays(7)->setTime(19, 0),
                'end_datetime' => now()->addDays(7)->setTime(21, 0),
                'location' => 'Ruang ' . $organization->name,
                'is_online' => false,
                'max_participants' => 30,
                'registration_required' => true,
                'registration_deadline' => now()->addDays(5),
                'status' => 'upcoming'
            ],
            [
                'title' => 'Pelatihan Public Speaking',
                'description' => 'Pelatihan untuk meningkatkan kemampuan public speaking bagi anggota ' . $organization->name . '. Akan dibimbing oleh trainer profesional.',
                'type' => 'training',
                'start_datetime' => now()->addDays(14)->setTime(9, 0),
                'end_datetime' => now()->addDays(14)->setTime(12, 0),
                'location' => 'Aula Utama',
                'is_online' => false,
                'max_participants' => 50,
                'registration_required' => true,
                'registration_deadline' => now()->addDays(10),
                'requirements' => ['Buku notes', 'Alat tulis', 'Minum'],
                'budget' => 1500000,
                'status' => 'upcoming'
            ],
            [
                'title' => 'Bakti Sosial Ramadhan',
                'description' => 'Kegiatan bakti sosial dalam rangka bulan Ramadhan. Kegiatan meliputi berbagi takjil dan santunan anak yatim.',
                'type' => 'social',
                'start_datetime' => now()->addDays(21)->setTime(15, 0),
                'end_datetime' => now()->addDays(21)->setTime(18, 0),
                'location' => 'Panti Asuhan Nurul Ihsan',
                'is_online' => false,
                'max_participants' => 100,
                'registration_required' => true,
                'registration_deadline' => now()->addDays(18),
                'requirements' => ['Baju sopan', 'Sedekat mungkin'],
                'budget' => 2500000,
                'status' => 'upcoming',
                'is_featured' => true
            ]
        ];

        foreach ($activities as $data) {
            // Jika admin user tidak ditemukan, lewati pembuatan aktivitas
            if (!$adminUser) {
                continue;
            }
            
            $activity = $organization->activities()->create(array_merge($data, [
                'created_by' => $adminUser->id,
                'registered_count' => rand(5, 20),
                'view_count' => rand(20, 100)
            ]));

            // Add some sample registrations
            $this->addSampleRegistrations($activity, $organization);
        }
    }

    private function createSampleAnnouncements($organization, $adminUser)
    {
        $announcements = [
            [
                'title' => 'Pengumuman: Perubahan Jadwal Rapat',
                'content' => 'Diberitahukan kepada seluruh anggota ' . $organization->name . ' bahwa jadwal rapat rutin yang semula diadakan pada hari Sabtu, pukul 19.00 WIB dialihkan menjadi hari Minggu, pukul 20.00 WIB. Perubahan ini efektif mulai pekan depan. Mohon kesediaannya untuk menyesuaikan jadwal. Terima kasih.',
                'type' => 'meeting',
                'priority' => 'high',
                'is_pinned' => true,
                'published_at' => now()->subDays(2)
            ],
            [
                'title' => 'Selamat! ' . $organization->name . ' Juara 1 Lomba',
                'content' => 'Alhamdulillah, dengan bangga kami umumkan bahwa ' . $organization->name . ' telah berhasil meraih juara 1 dalam Lomba ' . $this->getCompetitionName($organization) . ' tingkat provinsi. Prestasi ini berkat kerja keras dan dedikasi semua anggota. Mari kita pertahankan dan tingkatkan prestasi ini!',
                'type' => 'achievement',
                'priority' => 'normal',
                'is_pinned' => true,
                'published_at' => now()->subDays(5)
            ],
            [
                'title' => 'Reminder: Pendaftaran Anggota Baru',
                'content' => 'Bagi teman-teman yang ingin bergabung dengan ' . $organization->name . ', pendaftaran anggota baru akan ditutup pada tanggal ' . now()->addDays(10)->format('d F Y') . '. Segera daftarkan diri Anda melalui formulir yang tersedia di sekretariat atau online. Informasi lebih lanjut hubungi pengurus.',
                'type' => 'reminder',
                'priority' => 'normal',
                'is_pinned' => false,
                'published_at' => now()->subDays(1)
            ],
            [
                'title' => '🚨 PENTING: Pengumpulan Dokumentasi Kegiatan',
                'content' => 'Diharapkan kepada seluruh koordinator bidang untuk segera mengumpulkan dokumentasi kegiatan (foto, video, laporan) periode bulan laporan. Dokumen dikumpulkan paling lambat hari Jumat minggu ini ke sekretariat. Hal ini penting untuk laporan pertanggungjawaban organisasi.',
                'type' => 'urgent',
                'priority' => 'urgent',
                'is_pinned' => true,
                'published_at' => now()->subHours(6)
            ]
        ];

        foreach ($announcements as $data) {
            // Jika admin user tidak ditemukan, lewati pembuatan pengumuman
            if (!$adminUser) {
                continue;
            }
            
            $organization->announcements()->create(array_merge($data, [
                'author_id' => $adminUser->id,
                'view_count' => rand(30, 150),
                'read_count' => rand(20, 100)
            ]));
        }
    }

    private function addSampleRegistrations($activity, $organization)
    {
        // Get some members from the organization
        $members = $organization->activeMembers()->take(5)->get();
        
        foreach ($members as $member) {
            $status = rand(0, 10) > 2 ? 'registered' : 'confirmed';
            
            $activity->registrations()->create([
                'member_id' => $member->id,
                'registered_by' => 1, // Admin
                'status' => $status,
                'notes' => $status === 'confirmed' ? 'Sudah konfirmasi kehadiran' : 'Menunggu konfirmasi'
            ]);
        }
    }

    private function getCompetitionName($organization)
    {
        if (str_contains($organization->name, 'OSIS')) return 'OSIS';
        if (str_contains($organization->name, 'IPNU')) return 'Pildacil';
        if (str_contains($organization->name, 'IPPNU')) return 'Musabaqah';
        if (str_contains($organization->name, 'Pagar Nusa')) return 'Pencak Silat';
        if (str_contains($organization->name, 'Banser')) return 'Baris Berbaris';
        if (str_contains($organization->name, 'Qurra')) return 'Musabaqah Al-Qur\'an';
        
        return 'Organisasi';
    }
}
