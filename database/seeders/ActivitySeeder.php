<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Activity;
use Carbon\Carbon;

class ActivitySeeder extends Seeder
{
    public function run()
    {
        $activities = [
            [
                'title' => 'Annual School Science Fair',
                'description' => 'Showcase of student science projects from all grades with special awards and recognition.',
                'category' => 'Academic',
                'date' => Carbon::now()->addDays(30),
                'location' => 'School Auditorium',
                'status' => 'upcoming',
            ],
            [
                'title' => 'Sports Day 2025',
                'description' => 'Annual sports competition featuring track and field events, team sports, and athletic challenges.',
                'category' => 'Sports',
                'date' => Carbon::now()->addDays(15),
                'location' => 'School Sports Complex',
                'status' => 'upcoming',
            ],
            [
                'title' => 'Cultural Performance Night',
                'description' => 'Evening of dance, music, and dramatic performances celebrating our diverse cultural heritage.',
                'category' => 'Cultural',
                'date' => Carbon::now()->addDays(45),
                'location' => 'School Theater',
                'status' => 'upcoming',
            ],
            [
                'title' => 'Parent-Teacher Conference',
                'description' => 'Bi-annual meeting between parents and teachers to discuss student progress and development.',
                'category' => 'Academic',
                'date' => Carbon::now()->addDays(7),
                'location' => 'Classrooms',
                'status' => 'upcoming',
            ],
            [
                'title' => 'School Anniversary Celebration',
                'description' => 'Special events and activities marking another year of educational excellence.',
                'category' => 'Special Event',
                'date' => Carbon::now()->addDays(60),
                'location' => 'School Grounds',
                'status' => 'upcoming',
            ]
        ];

        foreach ($activities as $activity) {
            Activity::firstOrCreate(
                ['title' => $activity['title']],
                $activity
            );
        }
    }
}
