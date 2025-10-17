<?php
namespace App\Events;

class StudentRegistered
{
    public $student;
    public function __construct($student = null)
    {
        $this->student = $student;
    }
}
