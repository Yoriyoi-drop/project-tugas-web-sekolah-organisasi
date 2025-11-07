<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contact;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        $contacts = [
            [
                'name' => 'Rudi Hartono',
                'email' => 'rudi.hartono@email.com',
                'subject' => 'Informasi Pendaftaran',
                'message' => 'Saya ingin menanyakan tentang prosedur pendaftaran siswa baru untuk tahun ajaran 2025/2026.',
                'is_read' => true,
                'read_at' => now()
            ],
            [
                'name' => 'Dewi Susanti',
                'email' => 'dewi.susanti@email.com',
                'subject' => 'Jadwal Ujian',
                'message' => 'Mohon informasi mengenai jadwal ujian semester untuk kelas 12.',
                'is_read' => false,
                'read_at' => null
            ],
            [
                'name' => 'Hendra Kusuma',
                'email' => 'hendra.kusuma@email.com',
                'subject' => 'Konsultasi Akademik',
                'message' => 'Saya ingin mengajukan jadwal konsultasi dengan guru BK terkait pemilihan jurusan.',
                'is_read' => false,
                'read_at' => null
            ],
            [
                'name' => 'Rina Wati',
                'email' => 'rina.wati@email.com',
                'subject' => 'Kegiatan Ekstrakurikuler',
                'message' => 'Bagaimana prosedur pendaftaran untuk kegiatan ekstrakurikuler basket?',
                'is_read' => true,
                'read_at' => now()->subDays(2)
            ],
            [
                'name' => 'Bambang Prasetyo',
                'email' => 'bambang.prasetyo@email.com',
                'subject' => 'Pembayaran SPP',
                'message' => 'Mohon informasi mengenai prosedur pembayaran SPP secara online.',
                'is_read' => false,
                'read_at' => null
            ]
        ];

        foreach ($contacts as $contact) {
            Contact::firstOrCreate(
                ['email' => $contact['email'], 'subject' => $contact['subject']],
                $contact
            );
        }
    }
}
