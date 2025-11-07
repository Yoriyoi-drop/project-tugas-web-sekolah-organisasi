<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run()
    {
        $students = [
            [
                'name' => 'John Doe',
                'nis' => '2025001',
                'address' => 'Jl. Student No. 1',
                'phone' => '081234567890',
                'class' => '12 IPA 1',
                'email' => 'john.doe@student.com',
            ],
            [
                'name' => 'Jane Smith',
                'nis' => '2025002',
                'address' => 'Jl. Student No. 2',
                'phone' => '081234567891',
                'class' => '12 IPA 1',
                'email' => 'jane.smith@student.com',
            ],
            [
                'name' => 'Bob Wilson',
                'nis' => '2025003',
                'address' => 'Jl. Student No. 3',
                'phone' => '081234567892',
                'class' => '12 IPA 2',
                'email' => 'bob.wilson@student.com',
            ],
            [
                'name' => 'Alice Brown',
                'nis' => '2025004',
                'address' => 'Jl. Student No. 4',
                'phone' => '081234567893',
                'class' => '12 IPA 2',
                'email' => 'alice.brown@student.com',
            ],
            [
                'name' => 'Charlie Davis',
                'nis' => '2025005',
                'address' => 'Jl. Student No. 5',
                'phone' => '081234567894',
                'class' => '12 IPS 1',
                'email' => 'charlie.davis@student.com',
            ]
        ];

        foreach ($students as $student) {
            Student::firstOrCreate(
                ['nis' => $student['nis']],
                $student
            );
        }
    }
}
