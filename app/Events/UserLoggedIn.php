<?php
namespace App\Events;

class UserLoggedIn
{
    public $user;
    public function __construct($user = null)
    {
        $this->user = $user;
    }
}
