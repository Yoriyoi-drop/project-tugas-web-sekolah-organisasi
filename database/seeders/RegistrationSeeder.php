<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Registration;
use App\Models\Organization;

class RegistrationSeeder extends Seeder
{
    public function run(): void
    {
        if (Registration::count() > 0) {
            return;
        }

        $org = Organization::first();
        if (!$org) {
            return;
        }

        Registration::create([
            'organization_id' => $org->id,
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'phone' => '081234567890',
            'class' => 'XII IPA',
            'nis' => '1234567890',
            'address' => 'Jl. Contoh No.1',
            'motivation' => 'Ingin belajar dan berkontribusi.',
            'status' => 'pending',
        ]);
    }
}
