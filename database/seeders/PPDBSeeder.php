<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PPDB;
use Illuminate\Support\Str;

class PPDBSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ppdbData = [
            [
                'name' => 'Budi Santoso',
                'nik' => '3374121234567890',
                'email' => 'budi.santoso@email.com',
                'birth_date' => '2010-05-15',
                'birth_place' => 'Jakarta',
                'gender' => 'male',
                'address' => 'Jl. Pendidikan No. 123, Jakarta',
                'phone' => '081234567890',
                'parent_name' => 'Ahmad Santoso',
                'parent_phone' => '081234567891',
                'previous_school' => 'SMP Negeri 1 Jakarta',
                'desired_major' => 'IPA',
                'status' => 'pending'
            ],
            [
                'name' => 'Siti Rahma',
                'nik' => '3374121234567891',
                'email' => 'siti.rahma@email.com',
                'birth_date' => '2010-06-20',
                'birth_place' => 'Surabaya',
                'gender' => 'female',
                'address' => 'Jl. Pemuda No. 456, Surabaya',
                'phone' => '081234567892',
                'parent_name' => 'Bambang Rahmat',
                'parent_phone' => '081234567893',
                'previous_school' => 'SMP Negeri 2 Surabaya',
                'desired_major' => 'IPS',
                'status' => 'approved'
            ],
            [
                'name' => 'Deni Wijaya',
                'nik' => '3374121234567892',
                'email' => 'deni.wijaya@email.com',
                'birth_date' => '2010-07-10',
                'birth_place' => 'Bandung',
                'gender' => 'male',
                'address' => 'Jl. Merdeka No. 789, Bandung',
                'phone' => '081234567894',
                'parent_name' => 'Joko Wijaya',
                'parent_phone' => '081234567895',
                'previous_school' => 'SMP Negeri 3 Bandung',
                'desired_major' => 'IPA',
                'status' => 'rejected'
            ],
            [
                'name' => 'Maya Putri',
                'nik' => '3374121234567893',
                'email' => 'maya.putri@email.com',
                'birth_date' => '2010-08-25',
                'birth_place' => 'Semarang',
                'gender' => 'female',
                'address' => 'Jl. Diponegoro No. 321, Semarang',
                'phone' => '081234567896',
                'parent_name' => 'Hadi Putra',
                'parent_phone' => '081234567897',
                'previous_school' => 'SMP Negeri 1 Semarang',
                'desired_major' => 'IPS',
                'status' => 'pending'
            ]
        ];

        foreach ($ppdbData as $data) {
            PPDB::firstOrCreate(
                ['nik' => $data['nik']],
                $data
            );
        }
    }
}
