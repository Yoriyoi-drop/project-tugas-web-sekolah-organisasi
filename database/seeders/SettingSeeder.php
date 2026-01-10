<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'site_name' => 'MA NU Nusantara',
            'site_description' => 'Membentuk generasi santri yang berakhlak mulia, cerdas, dan siap menghadapi tantangan zaman.',
            'contact_email' => 'info@manusantara.sch.id',
            'contact_phone' => '(021) 1234-5678',
            'address' => 'Jl. Pendidikan No. 123, Kelurahan Nusantara, Jakarta Selatan 12345',
            'opening_hours' => 'Senin - Jumat: 07:00 - 16:00 / Sabtu: 07:00 - 12:00',
            'social_facebook' => 'https://facebook.com/manunusantara',
            'social_instagram' => 'https://instagram.com/manunusantara',
            'social_youtube' => 'https://youtube.com/manunusantara',
            'social_tiktok' => 'https://tiktok.com/@manunusantara',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
