<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PPDB;
use App\Models\Contact;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Activity;

class VerifyData extends Command
{
    protected $signature = 'app:verify-data';
    protected $description = 'Verify seeded data in the database';

    public function handle()
    {
        $this->info('Verifying seeded data...');

        // Check PPDB
        $this->info("\nPPDB Applications:");
        $ppdb = PPDB::all();
        foreach ($ppdb as $application) {
            $this->line("- {$application->name} ({$application->status})");
        }

        // Check Contacts
        $this->info("\nContacts:");
        $contacts = Contact::all();
        foreach ($contacts as $contact) {
            $readStatus = $contact->is_read ? 'Read' : 'Unread';
            $this->line("- {$contact->name}: {$contact->subject} ({$readStatus})");
        }

        // Check Students
        $this->info("\nStudents by Class:");
        $students = Student::all();
        foreach ($students as $student) {
            $this->line("- {$student->name} - Class: {$student->class}");
        }

        // Check Teachers
        $this->info("\nTeachers by Subject:");
        $teachers = Teacher::all();
        foreach ($teachers as $teacher) {
            $this->line("- {$teacher->name} - Subject: {$teacher->subject}");
        }

        // Check Activities
        $this->info("\nUpcoming Activities:");
        $activities = Activity::where('status', 'upcoming')->get();
        foreach ($activities as $activity) {
            $this->line("- {$activity->title} ({$activity->date})");
        }

        $this->info("\nVerification complete!");
    }
}
