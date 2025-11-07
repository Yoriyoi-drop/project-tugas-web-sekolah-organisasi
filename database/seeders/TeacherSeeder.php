<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;

class TeacherSeeder extends Seeder
{
    public function run()
    {
        $teachers = [
            [
                'name' => 'Dr. Sarah Johnson',
                'nip' => '202501001',
                'email' => 'sarah.johnson@school.com',
                'address' => 'Jl. Guru No. 1',
                'phone' => '081234567001',
                'subject' => 'Mathematics',
                'position' => 'Head of Mathematics Department',
            ],
            [
                'name' => 'Prof. Michael Chen',
                'nip' => '202501002',
                'email' => 'michael.chen@school.com',
                'address' => 'Jl. Guru No. 2',
                'phone' => '081234567002',
                'subject' => 'Physics',
                'position' => 'Senior Teacher',
            ],
            [
                'name' => 'Mrs. Emily Parker',
                'nip' => '202501003',
                'email' => 'emily.parker@school.com',
                'address' => 'Jl. Guru No. 3',
                'phone' => '081234567003',
                'subject' => 'English Literature',
                'position' => 'Language Coordinator',
            ],
            [
                'name' => 'Mr. Ahmad Rizki',
                'nip' => '202501004',
                'email' => 'ahmad.rizki@school.com',
                'address' => 'Jl. Guru No. 4',
                'phone' => '081234567004',
                'subject' => 'Biology',
                'position' => 'Science Lab Coordinator',
            ],
            [
                'name' => 'Ms. Siti Rahayu',
                'nip' => '202501005',
                'email' => 'siti.rahayu@school.com',
                'address' => 'Jl. Guru No. 5',
                'phone' => '081234567005',
                'subject' => 'History',
                'position' => 'Social Studies Coordinator',
            ]
        ];

        foreach ($teachers as $teacher) {
            Teacher::firstOrCreate(
                ['nip' => $teacher['nip']],
                $teacher
            );
        }
    }
}
