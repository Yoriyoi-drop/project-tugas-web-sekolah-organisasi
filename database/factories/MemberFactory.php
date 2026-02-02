<?php

namespace Database\Factories;

use App\Models\Member;
use App\Models\Organization;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemberFactory extends Factory
{
    protected $model = Member::class;

    public function definition()
    {
        $memberType = $this->faker->randomElement(['student', 'teacher']);
        
        return [
            'organization_id' => Organization::factory(),
            'student_id' => $memberType === 'student' ? Student::factory() : null,
            'teacher_id' => $memberType === 'teacher' ? Teacher::factory() : null,
            'role' => $this->faker->randomElement(['member', 'admin', 'moderator']),
            'position' => $this->faker->optional()->jobTitle,
            'period' => $this->faker->year,
            'status' => $this->faker->randomElement(['active', 'inactive', 'suspended']),
            'join_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'end_date' => $this->faker->optional(0.3)->dateTimeBetween('now', '+2 years'),
            'notes' => $this->faker->optional()->sentence,
            'achievements' => $this->faker->optional()->randomElements(['Best Member 2023', 'Perfect Attendance', 'Leadership Award'], $this->faker->numberBetween(0, 2)),
            'skills' => $this->faker->optional()->randomElements(['Leadership', 'Communication', 'Technical', 'Creative'], $this->faker->numberBetween(0, 3)),
        ];
    }
}
